<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Products.php';
require_once __DIR__ . '/../Models/Cart.php';

class CheckoutController extends Controller
{
    private $productModel;
    private $cartModel;
    protected $dbh;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $this->dbh = $database->getDbh();
        if ($this->dbh === null) {
            throw new Exception("Không thể kết nối đến cơ sở dữ liệu.");
        }
        $this->productModel = new Products();
        $this->cartModel = new Cart();
    }

    public function index()
    {
        $cart_items = [];
        $total = 0;
        $addresses = $this->getCustomerAddresses();
        $selected_address = isset($_SESSION['selected_address']) ? $_SESSION['selected_address'] : '';
        $message = $_SESSION['message'] ?? '';

        if (isset($_SESSION['customer_id'])) {
            // Lấy danh sách sản phẩm từ database thông qua Cart model
            $cart_items = $this->cartModel->getCartItems($_SESSION['customer_id']);
            foreach ($cart_items as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        $this->view('checkout/checkout', [
            'cart_items' => $cart_items,
            'total' => $total,
            'addresses' => $addresses,
            'selected_address' => $selected_address,
            'message' => $message
        ]);
        unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
    }

    public function saveAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_address'])) {
            $customer_id = $_SESSION['customer_id'] ?? null;
            if (!$customer_id) {
                $_SESSION['error_message'] = 'Vui lòng đăng nhập để lưu địa chỉ!';
                header('Location: /checkout');
                exit;
            }

            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $street = trim($_POST['street'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $country_code = trim($_POST['country_code'] ?? '');
            $detail = trim($_POST['detail'] ?? '');

            if (!empty($name) && !empty($phone) && !empty($street) && !empty($city) && !empty($country_code)) {
                $stmt = $this->dbh->prepare("INSERT INTO customer_address (customer_id, name, phone, street, city, country_code, detail) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$customer_id, $name, $phone, $street, $city, $country_code, $detail]);
                $lastId = $this->dbh->lastInsertId();
                $stmt = $this->dbh->prepare("SELECT name, phone, street, city, country_code, detail FROM customer_address WHERE id = ? AND customer_id = ?");
                $stmt->execute([$lastId, $customer_id]);
                $address = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['selected_address'] = json_encode($address);
                $_SESSION['message'] = 'Địa chỉ đã được lưu thành công!';
            } else {
                $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin!';
            }
            header('Location: /checkout');
            exit;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_address_id'])) {
            $address_id = $_POST['select_address_id'];
            $customer_id = $_SESSION['customer_id'] ?? null;
            if ($customer_id && is_numeric($address_id)) {
                $stmt = $this->dbh->prepare("SELECT name, phone, street, city, country_code, detail FROM customer_address WHERE id = ? AND customer_id = ?");
                $stmt->execute([$address_id, $customer_id]);
                $address = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($address) {
                    $_SESSION['selected_address'] = json_encode($address);
                    $_SESSION['message'] = 'Địa chỉ đã được chọn!';
                } else {
                    $_SESSION['error_message'] = 'Địa chỉ không hợp lệ!';
                }
            } else {
                $_SESSION['error_message'] = 'Dữ liệu gửi đi không hợp lệ: ' . json_encode($_POST);
            }
            header('Location: /checkout');
            exit;
        }
    }

    public function deleteAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_address_id'])) {
            $address_id = $_POST['delete_address_id'];
            $customer_id = $_SESSION['customer_id'] ?? null;

            if ($customer_id && is_numeric($address_id)) {
                $stmt = $this->dbh->prepare("DELETE FROM customer_address WHERE id = ? AND customer_id = ?");
                $result = $stmt->execute([$address_id, $customer_id]);

                if ($result) {
                    $_SESSION['message'] = 'Địa chỉ đã được xóa thành công!';
                    $addresses = $this->getCustomerAddresses();
                    if (!empty($addresses)) {
                        // Chọn địa chỉ đầu tiên làm mặc định nếu còn địa chỉ
                        $firstAddress = $addresses[0];
                        $_SESSION['selected_address'] = json_encode(array_diff_key($firstAddress, ['id' => '']));
                    } else {
                        unset($_SESSION['selected_address']);
                    }
                } else {
                    $_SESSION['error_message'] = 'Không thể xóa địa chỉ. Vui lòng thử lại!';
                }
            } else {
                $_SESSION['error_message'] = 'Địa chỉ không hợp lệ!';
            }
            header('Location: /checkout');
            exit;
        }
    }

    public function confirm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
            $customer_id = $_SESSION['customer_id'] ?? null;
            if (!$customer_id) {
                $_SESSION['error_message'] = 'Vui lòng đăng nhập để đặt hàng!';
                header('Location: /checkout');
                exit;
            }

            $address = json_decode($_POST['selected_address'] ?? '', true);
            $shipping_method = $_POST['shipping_method'] ?? 'standard';
            $payment_method = $_POST['payment_method'] ?? 'cod';
            $cart_items = $this->cartModel->getCartItems($customer_id);
            $total = 0;

            foreach ($cart_items as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            if (empty($address) || empty($cart_items)) {
                $_SESSION['error_message'] = 'Vui lòng chọn địa chỉ và kiểm tra giỏ hàng.';
                header('Location: /checkout');
                exit;
            }

            try {
                $order_id = $this->saveOrder($customer_id, $address, $shipping_method, $payment_method, $total);
                foreach ($cart_items as $item) {
                    $this->saveOrderDetail($order_id, $item['product_id'], $item['quantity'], $item['price']);
                    $this->updateStock($item['product_id'], $item['quantity']);
                    $this->cartModel->removeFromCart($customer_id, $item['product_id']);
                }
                $_SESSION['success_message'] = 'Đơn hàng đã được đặt thành công!';
                header('Location: /');
                exit;
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
                header('Location: /checkout');
                exit;
            }
        }
    }

    private function getCustomerAddresses()
    {
        $customer_id = $_SESSION['customer_id'] ?? null;
        if ($customer_id) {
            $stmt = $this->dbh->prepare("SELECT id, name, phone, street, city, country_code, detail FROM customer_address WHERE customer_id = ? ORDER BY id ASC");
            $stmt->execute([$customer_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    // Phương thức giả định (cần triển khai)
    private function saveOrder($customer_id, $address, $shipping_method, $payment_method, $total) { return 1; }
    private function saveOrderDetail($order_id, $product_id, $quantity, $price) {}
    private function updateStock($product_id, $quantity) {}
}
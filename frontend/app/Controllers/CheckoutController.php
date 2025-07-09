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
        $this->refreshCartIfNeeded(); // Làm mới giỏ hàng nếu cần
    }

    private function refreshCartIfNeeded()
    {
        $customerId = $_SESSION['customer_id'] ?? null;
        $customerSession = $_SESSION['customer_session'] ?? null;

        if (!$customerId && !$customerSession) {
            $_SESSION['customer_session'] = uniqid('guest_', true);
            $customerSession = $_SESSION['customer_session'];
        }

        $cartItems = $this->cartModel->getCartItems($customerId, $customerSession);
        $_SESSION['cart'] = $cartItems ?: [];
    }

    public function index()
    {
        $customerId = $_SESSION['customer_id'] ?? null;
        $customerSession = $_SESSION['customer_session'] ?? null;

        $cart_items = $this->cartModel->getCartItems($customerId, $customerSession);
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $addresses = $this->getCustomerAddresses();

        // Nếu chưa có selected_address, tự động chọn địa chỉ đầu tiên
        if (empty($_SESSION['selected_address']) && !empty($addresses)) {
            $_SESSION['selected_address'] = json_encode($addresses[0]);
        }

        $selected_address = $_SESSION['selected_address'] ?? '';
        $message = $_SESSION['message'] ?? '';

        $this->view('checkout/checkout', [
            'cart_items' => $cart_items,
            'total' => $total,
            'addresses' => $addresses,
            'selected_address' => $selected_address,
            'customer_id' => $customerId,
            'message' => $message
        ]);

        unset($_SESSION['message']);
    }

    public function saveAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_address'])) {
            $customer_id = $_SESSION['customer_id'] ?? null;
            if (!$customer_id && !isset($_SESSION['guest_info'])) {
                $_SESSION['error_message'] = 'Vui lòng đăng nhập hoặc nhập thông tin khách để lưu địa chỉ!';
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
                try {
                    $stmt = $this->dbh->prepare("INSERT INTO customer_address (customer_id, name, phone, street, city, country_code, detail) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$customer_id, $name, $phone, $street, $city, $country_code, $detail]);
                    $lastId = $this->dbh->lastInsertId();
                    $stmt = $this->dbh->prepare("SELECT id, name, phone, street, city, country_code, detail FROM customer_address WHERE id = ? AND customer_id = ?");
                    $stmt->execute([$lastId, $customer_id]);
                    $address = $stmt->fetch(PDO::FETCH_ASSOC);
                    $_SESSION['selected_address'] = json_encode($address); // Lưu toàn bộ địa chỉ bao gồm id
                    $_SESSION['message'] = 'Địa chỉ đã được lưu thành công!';
                } catch (PDOException $e) {
                    error_log("Database error in saveAddress: " . $e->getMessage());
                    $_SESSION['error_message'] = 'Lỗi khi lưu địa chỉ. Vui lòng thử lại! Chi tiết: ' . $e->getMessage();
                }
            } else {
                $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin!';
            }
            header('Location: /checkout');
            exit;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_address_id'])) {
            $address_id = $_POST['select_address_id'];
            $customer_id = $_SESSION['customer_id'] ?? null;
            if ($customer_id && is_numeric($address_id)) {
                $stmt = $this->dbh->prepare("SELECT id, name, phone, street, city, country_code, detail FROM customer_address WHERE id = ? AND customer_id = ?");
                $stmt->execute([$address_id, $customer_id]);
                $address = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($address) {
                    $_SESSION['selected_address'] = json_encode($address); // Lưu toàn bộ địa chỉ
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
                        $_SESSION['selected_address'] = json_encode($addresses[0]);
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
            $customerId = $_SESSION['customer_id'] ?? null;
            $customerSession = $_SESSION['customer_session'] ?? null;

            $address = json_decode($_POST['selected_address'] ?? '', true);
            $shipping_method = $_POST['shipping_method'] ?? 'standard';
            $payment_method = $_POST['payment_method'] ?? 'cod';

            // Guest info
            $customer_name = $customerId ? ($_SESSION['customer_name'] ?? '') : trim($_POST['customer_name'] ?? '');
            $customer_email = $customerId ? ($_SESSION['customer_email'] ?? '') : trim($_POST['customer_email'] ?? '');
            $customer_phone = $customerId ? ($_SESSION['customer_phone'] ?? '') : trim($_POST['customer_phone'] ?? '');

            if (!$customerId && (!empty($customer_name) || !empty($customer_email) || !empty($customer_phone))) {
                $_SESSION['guest_info'] = [
                    'name' => $customer_name,
                    'email' => $customer_email,
                    'phone' => $customer_phone
                ];
            }

            $cart_items = $this->cartModel->getCartItems($customerId, $customerSession);
            $total = 0;
            foreach ($cart_items as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            if (empty($address) || empty($cart_items) || (!$customerId && (empty($customer_name) || empty($customer_email) || empty($customer_phone)))) {
                $_SESSION['error_message'] = 'Vui lòng chọn địa chỉ và nhập đầy đủ thông tin!';
                header('Location: /checkout');
                exit;
            }

            $orderId = $this->saveOrder($customerId, $customer_name, $customer_email, $customer_phone, $address, $shipping_method, $payment_method, $total);
            if ($orderId) {
                foreach ($cart_items as $item) {
                    $this->saveOrderDetail($orderId, $item['product_id'], $item['quantity'], $item['price']);
                    $this->updateStock($item['product_id'], $item['quantity']);
                }
                $this->cartModel->clearCart($customerId, $customerSession);
                $_SESSION['message'] = 'Đặt hàng thành công!';
                header('Location: /order/success');
            } else {
                $_SESSION['error_message'] = 'Lỗi khi đặt hàng. Vui lòng thử lại!';
                header('Location: /checkout');
            }
            exit;
        }
    }

    private function getCustomerAddresses()
    {
        try {
            $customer_id = $_SESSION['customer_id'] ?? null;
            error_log("Fetching addresses for customerId: $customer_id");
            if (!$customer_id) {
                return [];
            }

            $stmt = $this->dbh->prepare("SELECT id, name, phone, street, city, country_code, detail FROM customer_address WHERE customer_id = ? ORDER BY id ASC");
            $stmt->execute([$customer_id]);
            $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Fetched addresses: " . print_r($addresses, true));
            return $addresses;
        } catch (PDOException $e) {
            error_log("Database error in getCustomerAddresses: " . $e->getMessage());
            return [];
        }
    }

    private function saveOrder($customer_id, $customer_name, $customer_email, $customer_phone, $address, $shipping_method, $payment_method, $total)
    {
        try {
            $stmt = $this->dbh->prepare("INSERT INTO orders (customer_id, customer_name, customer_email, customer_phone, address, shipping_method, payment_method, total, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())");
            $addressJson = json_encode($address);
            $stmt->execute([$customer_id, $customer_name, $customer_email, $customer_phone, $addressJson, $shipping_method, $payment_method, $total]);
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database error in saveOrder: " . $e->getMessage());
            return false;
        }
    }

    private function saveOrderDetail($order_id, $product_id, $quantity, $price)
    {
        try {
            $stmt = $this->dbh->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $product_id, $quantity, $price]);
        } catch (PDOException $e) {
            error_log("Database error in saveOrderDetail: " . $e->getMessage());
        }
    }

    private function updateStock($product_id, $quantity)
    {
        try {
            $stmt = $this->dbh->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?");
            $stmt->execute([$quantity, $product_id, $quantity]);
        } catch (PDOException $e) {
            error_log("Database error in updateStock: " . $e->getMessage());
        }
    }
}
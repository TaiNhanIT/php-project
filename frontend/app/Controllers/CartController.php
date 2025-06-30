<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Products.php';
require_once __DIR__ . '/../Models/Cart.php';

class CartController extends Controller
{
    private $productModel;
    private $cartModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->productModel = new Products();
        $this->cartModel = new Cart();
    }

    public function checkout()
    {
        $customerId = $_SESSION['customer_id'] ?? null;
        $error = '';
        $cartItems = [];
        $total = 0;
        $address = ''; // Lấy địa chỉ người dùng
        $paymentMethod = ''; // Phương thức thanh toán

        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!$customerId) {
            $error = 'Vui lòng đăng nhập để thanh toán!';
        } else {
            $cartItems = $this->cartModel->getCartItems($customerId);
            if (empty($cartItems)) {
                $error = 'Giỏ hàng của bạn trống.';
            } else {
                // Tính tổng giá trị giỏ hàng
                foreach ($cartItems as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
            }
        }

        // Kiểm tra phương thức thanh toán và địa chỉ giao hàng
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['address']) && !empty($_POST['address'])) {
                $address = $_POST['address'];
            } else {
                $error = 'Vui lòng nhập địa chỉ giao hàng!';
            }

            if (isset($_POST['payment_method']) && !empty($_POST['payment_method'])) {
                $paymentMethod = $_POST['payment_method'];
            } else {
                $error = 'Vui lòng chọn phương thức thanh toán!';
            }

            // Kiểm tra nếu có lỗi thì không thực hiện thanh toán
            if (empty($error)) {
                // Lưu đơn hàng vào cơ sở dữ liệu (tạo đơn hàng và thanh toán)
                // Thực hiện logic thanh toán ở đây (tuỳ thuộc vào phương thức thanh toán)
                // Sau khi thanh toán thành công, xóa giỏ hàng và chuyển hướng người dùng

                // Ví dụ: Gọi hàm tạo đơn hàng vào cơ sở dữ liệu
                // $this->createOrder($customerId, $cartItems, $address, $paymentMethod);

                // Xóa giỏ hàng sau khi thanh toán thành công
                foreach ($cartItems as $item) {
                    $this->cartModel->removeFromCart($customerId, $item['product_id']);
                }

                $_SESSION['success_message'] = 'Đặt hàng thành công!';
                header('Location: /checkout/thankyou');
                exit;
            }
        }

        $this->view('cart/checkout', [
            'error' => $error,
            'cartItems' => $cartItems,
            'total' => $total,
            'address' => $address,
            'paymentMethod' => $paymentMethod
        ]);
    }

    public function index()
    {
        $customerId = $_SESSION['customer_id'] ?? null;
        $error = '';
        $cartItems = [];
        $total = 0;

        if (!$customerId) {
            $error = 'Vui lòng đăng nhập để xem giỏ hàng!';
        } else {
            $cartItems = $this->cartModel->getCartItems($customerId);
            if (empty($cartItems)) {
                $error = 'Giỏ hàng của bạn trống.';
            } else {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['update'])) {
                        $productId = $_POST['product_id'];
                        $quantity = (int)$_POST['quantity'];
                        $this->cartModel->updateCartItem($customerId, $productId, $quantity);
                    } elseif (isset($_POST['remove'])) {
                        $productId = $_POST['product_id'];
                        $this->cartModel->removeFromCart($customerId, $productId);
                    }
                    header('Location: /cart/index');
                    exit;
                }

                foreach ($cartItems as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
            }
        }

        $this->view('cart/index', [
            'error' => $error,
            'cartItems' => $cartItems,
            'total' => $total,
            'customerId' => $customerId
        ]);
    }

    public function add($productId = '')
    {
        $customerId = $_SESSION['customer_id'] ?? null;
        if (!$customerId) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để thêm vào giỏ hàng!';
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            header('Location: ' . $referer);
            exit;
        }

        if (empty($productId) || !is_numeric($productId)) {
            $_SESSION['error_message'] = 'Sản phẩm không hợp lệ!';
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            header('Location: ' . $referer);
            exit;
        }

        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            $_SESSION['error_message'] = 'Sản phẩm không tồn tại hoặc hết hàng!';
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            header('Location: ' . $referer);
            exit;
        }

        if (empty($product['product_name']) || !isset($product['product_name'])) {
            error_log("Lỗi: Sản phẩm ID $productId không có product_name hợp lệ: " . print_r($product, true));
            $_SESSION['error_message'] = 'Dữ liệu sản phẩm không hợp lệ!';
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            header('Location: ' . $referer);
            exit;
        }

        $quantity = isset($_POST['quantity']) && is_numeric($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if ($quantity > $product['stock_quantity']) {
            $_SESSION['error_message'] = 'Số lượng vượt quá tồn kho!';
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            header('Location: ' . $referer);
            exit;
        }

        $result = $this->cartModel->addToCart($productId, $customerId);
        if ($result) {
            $_SESSION['success_message'] = 'Thêm vào giỏ hàng thành công!';
        } else {
            $_SESSION['error_message'] = 'Thêm vào giỏ hàng thất bại!';
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $referer);
        exit;
    }
}
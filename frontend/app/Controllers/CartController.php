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

        // Tạo customer_session nếu chưa có
        if (!isset($_SESSION['customer_session']) || empty($_SESSION['customer_session'])) {
            $_SESSION['customer_session'] = uniqid('guest_', true);
        }
        $this->productModel = new Products();
        $this->cartModel = new Cart();
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $customerId = $_SESSION['customer_id'] ?? null;
        $customerSession = $_SESSION['customer_session'];

        $cartItems = $this->cartModel->getCartItems($customerId, $customerSession);
        $_SESSION['cart'] = $cartItems ?: [];
    }

    public function index()
    {
        $customerId = $_SESSION['customer_id'] ?? null;
        $customerSession = $_SESSION['customer_session'];
        $cartItems = [];
        $total = 0;

        $this->refreshCart();
        $cartItems = $_SESSION['cart'];

        if (empty($cartItems)) {
            $error = 'Giỏ hàng của bạn trống.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update'])) {
                $productId = $_POST['product_id'];
                $quantity = max(1, (int)$_POST['quantity']);
                $this->cartModel->updateCartItem($customerId, $customerSession, $productId, $quantity);
                $this->refreshCart();
            } elseif (isset($_POST['remove'])) {
                $productId = $_POST['product_id'];
                $this->cartModel->removeFromCart($customerId, $customerSession, $productId);
                $this->refreshCart();
            }
            header('Location: /cart/index');
            exit;
        }

        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $this->view('cart/index', [
            'cartItems' => $cartItems,
            'total' => $total,
            'error' => $error ?? null,
            'customerId' => $customerId
        ]);
    }

    public function add($productId = '')
    {
        try {
            $customerId = $_SESSION['customer_id'] ?? null;
            $customerSession = $_SESSION['customer_session'];

            if (empty($productId) || !is_numeric($productId)) {
                $_SESSION['error_message'] = 'Sản phẩm không hợp lệ!';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
                exit;
            }

            $product = $this->productModel->getProductById($productId);
            if (!$product) {
                $_SESSION['error_message'] = 'Sản phẩm không tồn tại hoặc hết hàng!';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
                exit;
            }

            $quantity = isset($_POST['quantity']) && is_numeric($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            if ($quantity > ($product['stock_quantity'] ?? 0)) {
                $_SESSION['error_message'] = 'Số lượng vượt quá tồn kho!';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
                exit;
            }

            // Kiểm tra trong cart
            $cartItems = $this->cartModel->getCartItems($customerId, $customerSession);
            $found = false;
            foreach ($cartItems as $item) {
                if ($item['product_id'] == $productId) {
                    $newQuantity = $item['quantity'] + $quantity;
                    $this->cartModel->updateCartItem($customerId, $customerSession, $productId, $newQuantity);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $this->cartModel->addToCart($customerId, $customerSession, $productId, $quantity);
            }

            $this->refreshCart();
            $_SESSION['success_message'] = 'Đã thêm vào giỏ hàng!';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        } catch (Exception $e) {
            error_log("Error in CartController::add - " . $e->getMessage());
            $_SESSION['error_message'] = 'Lỗi hệ thống khi thêm sản phẩm!';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }
    }
}

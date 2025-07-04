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
        $this->refreshCart(); // Làm mới giỏ hàng khi khởi tạo
    }

    public function refreshCart()
    {
        $customerId = $_SESSION['customer_id'] ?? null;
        if ($customerId) {
            $cartItems = $this->cartModel->getCartItems($customerId);
            $_SESSION['cart'] = $cartItems ?: [];
        } else {
            $_SESSION['cart'] = [];
        }
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
            $this->refreshCart(); // Đảm bảo giỏ hàng luôn cập nhật
            $cartItems = $_SESSION['cart'];
            if (empty($cartItems)) {
                $error = 'Giỏ hàng của bạn trống.';
            } else {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['update'])) {
                        $productId = $_POST['product_id'];
                        $quantity = (int)$_POST['quantity'];
                        $success = $this->cartModel->updateCartItem($customerId, $productId, $quantity > 0 ? $quantity : 1);
                        if ($success) {
                            $this->refreshCart(); // Làm mới session sau khi cập nhật
                        } else {
                            error_log("Failed to update cart item: customerId=$customerId, productId=$productId, quantity=$quantity");
                        }
                    } elseif (isset($_POST['remove'])) {
                        $productId = $_POST['product_id'];
                        $success = $this->cartModel->removeFromCart($customerId, $productId);
                        if ($success) {
                            $this->refreshCart(); // Làm mới session sau khi xóa
                        } else {
                            error_log("Failed to remove cart item: customerId=$customerId, productId=$productId");
                        }
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
        try {
            $customerId = $_SESSION['customer_id'] ?? null;
            error_log("Attempting to add product with ID: $productId, Customer ID: " . ($customerId ?? 'Not set') . ", URL: " . $_SERVER['REQUEST_URI']);

            if (!$customerId) {
                $_SESSION['error_message'] = 'Vui lòng đăng nhập để thêm vào giỏ hàng!';
                $referer = $_SERVER['HTTP_REFERER'] ?? '/';
                header('Location: ' . $referer);
                exit;
            }

            if (empty($productId) || !is_numeric($productId)) {
                error_log("Invalid product ID received: $productId, from URL: " . $_SERVER['REQUEST_URI']);
                $_SESSION['error_message'] = 'Sản phẩm không hợp lệ! (ID không đúng: ' . htmlspecialchars($productId) . ')';
                $referer = $_SERVER['HTTP_REFERER'] ?? '/';
                header('Location: ' . $referer);
                exit;
            }

            $product = $this->productModel->getProductById($productId);
            error_log("Product fetched for ID $productId: " . print_r($product, true));
            if (!$product) {
                error_log("Product not found for ID: $productId");
                $_SESSION['error_message'] = 'Sản phẩm không tồn tại hoặc hết hàng!';
                $referer = $_SERVER['HTTP_REFERER'] ?? '/';
                header('Location: ' . $referer);
                exit;
            }

            if (empty($product['product_name']) || !isset($product['product_name'])) {
                error_log("Invalid product data for ID $productId: " . print_r($product, true));
                $_SESSION['error_message'] = 'Dữ liệu sản phẩm không hợp lệ!';
                $referer = $_SERVER['HTTP_REFERER'] ?? '/';
                header('Location: ' . $referer);
                exit;
            }

            $quantity = isset($_POST['quantity']) && is_numeric($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            error_log("Requested quantity: $quantity, Stock: " . ($product['stock_quantity'] ?? 'N/A'));
            if ($quantity > ($product['stock_quantity'] ?? 0)) {
                $_SESSION['error_message'] = 'Số lượng vượt quá tồn kho!';
                $referer = $_SERVER['HTTP_REFERER'] ?? '/';
                header('Location: ' . $referer);
                exit;
            }

            $found = false;
            $cartItems = $this->cartModel->getCartItems($customerId); // Lấy từ database
            foreach ($cartItems as &$item) {
                if ($item['product_id'] == $productId) {
                    $newQuantity = $item['quantity'] + $quantity;
                    $item['quantity'] = $newQuantity;
                    $this->cartModel->updateCartItem($customerId, $productId, $newQuantity);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $cartItems[] = [
                    'product_id' => $productId,
                    'product_name' => $product['product_name'] ?? 'Tên không xác định',
                    'price' => $product['price'] ?? 0,
                    'quantity' => $quantity,
                    'image' => $product['image'] ?? 'default.jpg'
                ];
                $this->cartModel->addToCart($customerId, $productId, $quantity);
            }
            $_SESSION['cart'] = $cartItems; // Đồng bộ với database

            $_SESSION['success_message'] = 'Thêm vào giỏ hàng thành công!';
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            header('Location: ' . $referer);
            exit;
        } catch (Exception $e) {
            error_log("Error in add: " . $e->getMessage() . ", URL: " . $_SERVER['REQUEST_URI']);
            $_SESSION['error_message'] = 'Lỗi hệ thống khi thêm sản phẩm!';
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            header('Location: ' . $referer);
            exit;
        }
    }
}
<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/Cart.php';

class OrderController extends Controller
{
    private $orderModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->orderModel = new Order();
    }
    public function confirm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer_id = $_SESSION['customer_id'] ?? null;
            $address = json_decode($_POST['selected_address'] ?? '', true);
            $shipping_method = $_POST['shipping_method'] ?? 'standard';
            $payment_method = $_POST['payment_method'] ?? 'cod';

            $cart = new Cart();
            $cart_items = $cart->getCartItems($customer_id);
            $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart_items));

            if (empty($cart_items) || !$address) {
                $_SESSION['error_message'] = "Vui lòng kiểm tra giỏ hàng và địa chỉ.";
                header('Location: /checkout');
                exit;
            }

            // Tạo đơn hàng
            $orderModel = new Order();
            $order_id = $orderModel->createOrder([
                'customer_id' => $customer_id,
                'customer_name' => $address['name'],
                'customer_email' => '', // Bạn có thể lấy từ $_SESSION nếu có
                'customer_phone' => $address['phone'],
                'customer_address' => $address['street'] . ', ' . $address['city'] . ', ' . $address['country_code'],
                'total_price' => $total,
                'shipping_method' => $shipping_method,
                'payment_method' => $payment_method,
                'status_id' => 1, // pending
            ]);

            // Lưu từng sản phẩm
            foreach ($cart_items as $item) {
                $orderModel->addOrderItem($order_id, $item['product_id'], $item['quantity'], $item['price']);
                $orderModel->decreaseProductStock($item['product_id'], $item['quantity']);
                $cart->removeFromCart($customer_id, $item['product_id']);
            }

            $_SESSION['success_message'] = 'Đơn hàng đã được đặt!';
            header("Location: /order/detail/$order_id");
            exit;
        }
    }

    public function index()
    {
        $customerId = $_SESSION['customer_id'] ?? null;
        $orders = $this->orderModel->getOrders($customerId);

        $this->view('order/index', [
            'orders' => $orders
        ]);
    }

    public function detail($id)
    {
        $orderModel = new Order();
        $order = $orderModel->getOrderById($id);
        $items = $orderModel->getOrderItems($id);

        if (!$order) {
            $_SESSION['error_message'] = 'Không tìm thấy đơn hàng!';
            header('Location: /');
            exit;
        }

        $this->view('order/detail', [
            'order' => $order,
            'items' => $items
        ]);
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $statusId = $_POST['status_id'] ?? null;

            if ($orderId && $statusId) {
                $this->orderModel->updateStatus($orderId, $statusId);
                $_SESSION['success_message'] = 'Cập nhật trạng thái thành công.';
            } else {
                $_SESSION['error_message'] = 'Thiếu thông tin để cập nhật.';
            }

            header('Location: /order/detail/' . $orderId);
            exit;
        }
    }

    public function cancel($id)
    {
        $this->orderModel->cancelOrder($id);
        $_SESSION['success_message'] = 'Đơn hàng đã được huỷ.';
        header('Location: /order');
        exit;
    }
}

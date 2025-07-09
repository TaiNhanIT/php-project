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
            $customer_session = $_SESSION['customer_session'] ?? null;

            // Địa chỉ: nếu là khách chưa đăng nhập thì lấy từ POST

            if (!$customer_id) {
                $address = [
                    'name' => $_POST['customer_name'] ?? '',
                    'phone' => $_POST['customer_phone'] ?? '',
                    'street' => $_POST['street'] ?? '',
                    'city' => $_POST['city'] ?? '',
                    'country_code' => $_POST['country_code'] ?? '',
                    'detail' => $_POST['detail'] ?? ''
                ];
            } else {
                // Nếu đã đăng nhập, lấy từ selected_address
                $address = json_decode($_POST['selected_address'] ?? '', true);
            }

            $shipping_method = $_POST['shipping_method'] ?? 'standard';
            $payment_method = $_POST['payment_method'] ?? 'cod';

            // Lấy giỏ hàng theo login hoặc guest session
            $cart = new Cart();
            $cart_items = $cart->getCartItems($customer_id, $customer_session);
            $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart_items));

            // Kiểm tra dữ liệu bắt buộc
            if (empty($cart_items) || empty($address) || empty($address['name']) || empty($address['phone'])) {
                $_SESSION['error_message'] = "Vui lòng kiểm tra giỏ hàng và địa chỉ.";
                header('Location: /checkout');
                exit;
            }

            $customer_name = $address['name'];
            $customer_phone = $address['phone'];

            $customer_email = $customer_id ? ($_SESSION['customer_email'] ?? '') : ($_POST['customer_email'] ?? '');
            if (!$customer_id && !empty($customer_email)) {
                $_SESSION['customer_email'] = $customer_email;
            }
            if (!$customer_id && empty($customer_email)) {
                $_SESSION['error_message'] = "Vui lòng nhập địa chỉ email.";
                header('Location: /checkout');
                exit;
            }

            $customer_address = json_encode([
                'street' => $address['street'] ?? '',
                'city' => $address['city'] ?? '',
                'country_code' => $address['country_code'] ?? '',
                'detail' => $address['detail'] ?? ''
            ]);

            $order_id = $this->orderModel->createOrder([
                'customer_id' => $customer_id,
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'customer_address' => $customer_address,
                'total' => $total,
                'shipping_method' => $shipping_method,
                'payment_method' => $payment_method,
                'status_id' => 1, // Đang chờ xử lý
            ]);

            foreach ($cart_items as $item) {
                $this->orderModel->addOrderItem($order_id, $item['product_id'], $item['quantity'], $item['price']);
                $this->orderModel->decreaseProductStock($item['product_id'], $item['quantity']);
                $cart->removeFromCart($customer_id, $customer_session, $item['product_id']);
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
            'orders' => $orders,
            'customer_id' => $customerId
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
            'items' => $items,
            'customer_id' => $_SESSION['customer_id'] ?? null
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
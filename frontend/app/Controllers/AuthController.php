<?php

use Services\MailService;

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../../Services/MailService.php'; // Đảm bảo đường dẫn đúng
require_once __DIR__ . '/../Controllers/CartController.php'; // Thêm để gọi refreshCart

class AuthController extends Controller
{
    private $cartController;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->cartController = new CartController(); // Khởi tạo để gọi refreshCart
    }

    public function login()
    {
        $loginError = '';
        $registerSuccess = '';

        if (isset($_SESSION['register_success'])) {
            $registerSuccess = $_SESSION['register_success'];
            unset($_SESSION['register_success']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $customerModel = new Customer();
            $customer = $customerModel->getCustomerByEmail($email);

            if ($customer && password_verify($password, $customer['password'])) {
                $_SESSION['customer_id'] = $customer['id'];
                $_SESSION['customer_email'] = $customer['email'];
                $_SESSION['customer_name'] = trim($customer['first_name'] . ' ' . $customer['last_name']);
                // Làm mới giỏ hàng sau khi đăng nhập
                $this->cartController->refreshCart();
                header('Location: /');
                exit;
            } else {
                $loginError = 'Email hoặc mật khẩu không đúng!';
            }
        } elseif (isset($_SESSION['login_email']) && isset($_SESSION['login_password'])) {
            $email = $_SESSION['login_email'];
            $password = $_SESSION['login_password'];
            unset($_SESSION['login_email'], $_SESSION['login_password']);
        }

        $this->view('customer/login', [
            'loginError' => $loginError,
            'registerSuccess' => $registerSuccess,
        ]);
    }

    public function register()
    {
        $register_error = '';
        $form_data = $_POST ?? [];

        // Prefill email from GET nếu có
        $form_data['email'] = $_GET['email'] ?? ($form_data['email'] ?? '');
        $order_id = $_GET['order_id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $form_data = [
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'confirm_password' => trim($_POST['confirm_password'] ?? ''),
                'phone' => trim($_POST['phone'] ?? '')
            ];

            $agree_terms = isset($_POST['agree_terms']);

            // Kiểm tra dữ liệu hợp lệ
            if (
                empty($form_data['full_name']) || empty($form_data['email']) ||
                empty($form_data['password']) || empty($form_data['confirm_password'])
            ) {
                $register_error = "Vui lòng nhập đầy đủ thông tin!";
            } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
                $register_error = "Email không hợp lệ!";
            } elseif ($form_data['password'] !== $form_data['confirm_password']) {
                $register_error = "Mật khẩu xác nhận không khớp!";
            } elseif (strlen($form_data['password']) < 6) {
                $register_error = "Mật khẩu phải có ít nhất 6 ký tự!";
            } elseif (!$agree_terms) {
                $register_error = "Bạn phải đồng ý với điều khoản!";
            } else {
                try {
                    $customerModel = new Customer();

                    // Kiểm tra email đã tồn tại
                    if ($customerModel->emailExists($form_data['email'])) {
                        $register_error = "Email đã tồn tại!";
                    } else {
                        // Tách họ tên
                        $name_parts = explode(' ', $form_data['full_name']);
                        $last_name = array_pop($name_parts);
                        $first_name = implode(' ', $name_parts);

                        $hashedPassword = password_hash($form_data['password'], PASSWORD_DEFAULT);

                        // Tạo tài khoản khách hàng
                        $customer_id = $customerModel->addCustomer([
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'email' => $form_data['email'],
                            'phone_number' => $form_data['phone'],
                            'password' => $hashedPassword
                        ]);

                        if ($customer_id) {
                            // Gán đơn hàng cho tài khoản nếu có order_id
                            if (!empty($order_id)) {
                                require_once __DIR__ . '/../Models/Order.php';
                                $orderModel = new Order();
                                $orderModel->assignOrderToCustomer((int)$order_id, $customer_id);
                                $_SESSION['success_message'] = "Tài khoản đã được tạo. Đơn hàng đã được liên kết.";
                            } else {
                                $_SESSION['success_message'] = "Tài khoản đã được tạo thành công!";
                            }

                            // Tự động đăng nhập
                            $_SESSION['customer_id'] = $customer_id;
                            $_SESSION['customer_email'] = $form_data['email'];
                            $_SESSION['customer_name'] = $form_data['full_name'];

                            header("Location: /order");
                            exit;
                        } else {
                            $register_error = "Đăng ký thất bại do lỗi hệ thống!";
                        }
                    }
                } catch (Exception $e) {
                    $register_error = "Lỗi hệ thống: " . $e->getMessage();
                    error_log("Registration error: " . $e->getMessage());
                }
            }
        }

        $this->view('customer/login', [
            'register_error' => $register_error,
            'form_data' => $form_data
        ]);
    }


    public function forgotPassword()
    {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $customerModel = new Customer();
            $customer = $customerModel->getCustomerByEmail($email);

            if ($customer) {
                $token = bin2hex(random_bytes(32));
                if ($customerModel->saveResetToken($email, $token)) {
                    $mailService = new MailService();
                    $result = $mailService->sendResetPasswordEmail($email, $token);
                    $success = $result['message'];
                } else {
                    $error = 'Lỗi khi lưu token.';
                }
            } else {
                $error = 'Email không tồn tại.';
            }
        }

        $this->view('customer/forgotPassword', ['error' => $error, 'success' => $success]);
    }

    public function resetPassword($token = '')
    {
        $error = '';
        $success = '';

        if (empty($token)) {
            $error = 'Token không hợp lệ.';
        } else {
            $customerModel = new Customer();
            $customer = $customerModel->getCustomerByToken($token);

            if (!$customer) {
                $error = 'Token không hợp lệ hoặc không tồn tại.';
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $password = trim($_POST['password'] ?? '');
                $confirmPassword = trim($_POST['confirm_password'] ?? '');

                if (empty($password) || empty($confirmPassword)) {
                    $error = 'Vui lòng nhập mật khẩu.';
                } elseif ($password !== $confirmPassword) {
                    $error = 'Mật khẩu không khớp.';
                } elseif (strlen($password) < 6) {
                    $error = 'Mật khẩu phải ít nhất 6 ký tự.';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    if ($customerModel->updatePassword($customer['email'], $hashedPassword)) {
                        $customerModel->clearResetToken($customer['email']);
                        $success = 'Mật khẩu đã được đặt lại. Vui lòng đăng nhập.';
                        header('Location: /?url=auth/login');
                        exit;
                    } else {
                        $error = 'Lỗi khi cập nhật mật khẩu.';
                    }
                }
            }
        }

        $this->view('customer/resetPassword', ['error' => $error, 'success' => $success, 'token' => $token]);
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Xóa thông tin đăng nhập, nhưng giữ lại customer_session và cart
        unset($_SESSION['customer_id']);
        unset($_SESSION['customer_email']);
        unset($_SESSION['customer_name']);

        // Không xóa $_SESSION['customer_session'] và $_SESSION['cart'] để giữ giỏ hàng

        header('Location: /auth/login');
        exit;
    }

}
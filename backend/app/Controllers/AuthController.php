<?php
require_once __DIR__ . '/../Core/Controller.php';

class AuthController extends Controller {

	public function login() {
        $login_error = '';
        $register_success = '';
        if (isset($_SESSION['customer_id']) && isset($_SESSION['customer_email'])) {
            $email = $_SESSION['customer_email'];
            $password = $_SESSION['last_login_password'] ?? '';
        }
        // Nếu vừa chuyển từ đăng ký sang (chưa POST), lấy từ session
        else if ($_SERVER['REQUEST_METHOD'] !== 'POST' && (isset($_SESSION['login_email']) || isset($_SESSION['login_password']))) {
            $email = $_SESSION['login_email'] ?? '';
            $password = $_SESSION['login_password'] ?? '';
            unset($_SESSION['login_email'], $_SESSION['login_password']);
        } else {
            // Nếu vừa submit form, lấy từ POST
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
        }

        if (isset($_SESSION['register_success'])) {
            $register_success = $_SESSION['register_success'];
            unset($_SESSION['register_success']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($email);
            $password = trim($password);

            $customerModel = new Customer();
            $customer = $customerModel->getCustomerByEmail($email);

            // Sử dụng password_verify để kiểm tra mật khẩu đã mã hóa
            if ($customer && password_verify($password, $customer['password'])) {
                $_SESSION['customer_id'] = $customer['id'];
                $_SESSION['customer_email'] = $customer['email'];
                $_SESSION['last_login_password'] = $password;
                header('Location: /SamSung/');
                exit;
            } else {
                $login_error = 'Email hoặc mật khẩu không đúng!';
            }
        }
        // Truyền dữ liệu vào view
        $this->view('customer/login');
    }

    public function register() {
        $register_error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $street = trim($_POST['street'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $country_code = trim($_POST['country_code'] ?? '');

            if (!$first_name || !$last_name || !$email || !$phone || !$password) {
                $register_error = "Vui lòng nhập đầy đủ thông tin!";
            } else {
                $customerModel = new Customer();
                if ($customerModel->emailExists($email)) {
                    $register_error = "Email đã tồn tại!";
                } else {
                    $customer_id = $customerModel->addCustomer([
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'phone_number' => $phone,
                        'password' => password_hash($password, PASSWORD_DEFAULT)
                    ]);
                    if ($customer_id) {
                        $customerModel->addAddress($customer_id, $street, $city, $country_code);
                        $_SESSION['register_success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                        $_SESSION['login_email'] = $email;
                        $_SESSION['login_password'] = $password;
                        header('Location: /SamSung/?controller=auth&action=login');
                        exit;
                    } else {
                        $register_error = "Đăng ký thất bại!";
                    }
                }
            }
        }
        // Truyền dữ liệu vào view
        $this->view('customer/register');
    }
}

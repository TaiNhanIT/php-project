<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Products.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Models/Order.php';

class CustomerController extends Controller {

    public function index() {
        $customerModel = new Customer();
        $perPage = 6;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $perPage;

        $customers = $customerModel->getCustomersWithAddresses($perPage, $offset);
        $totalCustomers = $customerModel->countCustomers();
        $totalPages = ceil($totalCustomers / $perPage);

        require __DIR__ . '/../views/customer/listCustomers.php';
    }

    public function detail($id) {
        $customerModel = new Customer();
        $customer = $customerModel->getCustomerById($id);
        require_once __DIR__ . '/../views/customer/detail.php';
    }

    public function edit($id) {
        $customerModel = new Customer();
        $error = '';
        $customer = $customerModel->getCustomerById($id);
        $addresses = $customerModel->getCustomerAddresses($id);

        // Thêm địa chỉ
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
            $street = trim($_POST['new_street']);
            $city = trim($_POST['new_city']);
            $country_code = trim($_POST['new_country_code']);
            if ($street !== '' && $city !== '' && $country_code !== '') {
                $customerModel->addAddress($id, $street, $city, $country_code);
                $addresses = $customerModel->getCustomerAddresses($id);
            } else {
                $error = "Vui lòng nhập đầy đủ thông tin địa chỉ!";
            }
        }

        // Xóa địa chỉ
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_address'])) {
            $addressId = $_POST['delete_address'];
            $customerModel->deleteAddress($addressId);
            $addresses = $customerModel->getCustomerAddresses($id);
        }

        // Sửa thông tin khách hàng và địa chỉ
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['add_address']) && !isset($_POST['delete_address'])) {
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $phone_number = trim($_POST['phone_number']);
            $password = trim($_POST['password']);

            if ($first_name === '' || $last_name === '' || $phone_number === '') {
                $error = "Vui lòng nhập đầy đủ thông tin!";
            } else {
                $updateData = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone_number' => $phone_number
                ];
                if ($password !== '') {
                    $updateData['password'] = $password;
                }
                $customerModel->updateCustomer($id, $updateData);

                // Cập nhật địa chỉ
                if (isset($_POST['addresses']) && is_array($_POST['addresses'])) {
                    foreach ($_POST['addresses'] as $addrId => $addr) {
                        $customerModel->updateAddress(
                            $addrId,
                            $addr['street'],
                            $addr['city'],
                            $addr['country_code']
                        );
                    }
                }
                header('Location: /Customer/index');
                exit;
            }
        }

        require __DIR__ . '/../views/customer/edit.php';
    }

    public function delete($id) {
        $customerModel = new Customer();
        $result = $customerModel->deleteCustomer($id);
        header('Location: /Customer/index');
        exit;
    }

    public function dashboard() {
        if (!isset($_SESSION['customer_id'])) {
            header('Location: /login');
            exit;
        }

        $customerModel = new Customer();
        $orderModel = new Order();
        $customer = $customerModel->getCustomerById($_SESSION['customer_id']);
        $orders = $orderModel->getOrders($_SESSION['customer_id']);

        $this->view('customer/dashboard', [
            'customer' => $customer,
            'orders' => $orders,
            'customerModel' => $customerModel
        ]);
    }
    public function addressBook() {
        if (!isset($_SESSION['customer_id'])) {
            header('Location: /login');
            exit;
        }

        $customerModel = new Customer();
        $customerId = $_SESSION['customer_id'];
        $error = '';

        // Thêm địa chỉ
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
            $street = trim($_POST['new_street']);
            $city = trim($_POST['new_city']);
            $country = trim($_POST['new_country_code']);
            if ($street && $city && $country) {
                $customerModel->addAddress($customerId, $street, $city, $country);
            } else {
                $error = "Vui lòng nhập đầy đủ thông tin.";
            }
        }

        // Xóa địa chỉ
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_address'])) {
            $addrId = $_POST['delete_address'];
            $customerModel->deleteAddress($addrId);
        }

        // Sửa địa chỉ
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addresses'])) {
            foreach ($_POST['addresses'] as $addr) {
                $customerModel->updateAddress(
                    $addr['id'],
                    $addr['street'],
                    $addr['city'],
                    $addr['country_code']
                );
            }
        }

        $addresses = $customerModel->getCustomerAddresses($customerId);

        $this->view('customer/addressBook', [
            'addresses' => $addresses,
            'error' => $error
        ]);
    }
    //Trang hiển thị Address Book riêng
    public function address() {
        if (!isset($_SESSION['customer_id'])) {
            header('Location: /login');
            exit;
        }

        $customerModel = new Customer();
        $addresses = $customerModel->getCustomerAddresses($_SESSION['customer_id']);

        $this->view('customer/address', [
            'addresses' => $addresses
        ]);
    }

    // ✅ Trang hiển thị danh sách Orders riêng
    public function orders() {
        if (!isset($_SESSION['customer_id'])) {
            header('Location: /login');
            exit;
        }

        $orderModel = new Order();
        $orders = $orderModel->getOrders($_SESSION['customer_id']);

        $this->view('customer/orders', [
            'orders' => $orders
        ]);
    }

    // ✅ Xử lý thêm địa chỉ
    public function addAddress() {
        if (!isset($_SESSION['customer_id'])) {
            header('Location: /login');
            exit;
        }

        $street = trim($_POST['street'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $country = trim($_POST['country_code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $detail = trim($_POST['detail'] ?? '') ?: null;  // sẽ là null nếu rỗng

        if ($street && $city && $country && $name && $phone) {
            $customerModel = new Customer();
            $customerModel->addAddress($_SESSION['customer_id'], $street, $city, $country, $name, $phone, $detail);

            $_SESSION['flash_message'] = "Thêm mới địa chỉ thành công";
        } else {
            $_SESSION['flash_message'] = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
        }

        header('Location: /customer/address');
        exit;
    }



    // ✅ Xử lý xoá địa chỉ
    public function deleteAddress() {
        if (!isset($_SESSION['customer_id']) || empty($_POST['address_id'])) {
            header('Location: /login');
            exit;
        }

        $customerModel = new Customer();
        $customerModel->deleteAddress((int)$_POST['address_id']);
        $_SESSION['flash_message'] = " Đã xóa địa chỉ.";
        header('Location: /customer/address');
        exit;
    }
    public function editAddress() {
        if (!isset($_SESSION['customer_id'])) {
            header('Location: /login');
            exit;
        }

        $id = $_POST['id'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $country = $_POST['country_code'];

        $customerModel = new Customer();
        $customerModel->updateAddress($id, $street, $city, $country);

        $_SESSION['flash_message'] = "✏️ Đã cập nhật địa chỉ.";
        header('Location: /customer/address');
        exit;
    }
}
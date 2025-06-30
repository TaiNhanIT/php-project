<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Products.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Customer.php';
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
        $customerModel = new Customer(); // Tạo đối tượng model Customer
        $customer = $customerModel->getCustomerById($id); // Lấy thông tin khách hàng theo id
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
        $customer = $customerModel->getCustomerById($_SESSION['customer_id']);

        require __DIR__ . '/../views/customer/dashboard.php';
    }
}

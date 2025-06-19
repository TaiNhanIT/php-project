<?php
require_once __DIR__ . '/../../config/database.php';

class Customer extends Database
{
    public function __construct()
    {
        parent::__construct();
        if ($this->dbh === null) {
            throw new Exception("Kết nối cơ sở dữ liệu thất bại!");
        }
    }

    public function getAllCustomers()
    {
        $this->query('SELECT * FROM customers');
        return $this->resultSet();
    }

    public function getCustomerById($id)
    {
        $this->query('SELECT * FROM customers WHERE id = :id');
        $this->bind(':id', $id);
        return $this->single();
    }

    public function emailExists($email)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ? true : false;
    }

    public function getCustomerByEmail($email)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCustomerAddresses($customerId)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("SELECT id, street, city, country_code FROM customer_address WHERE customer_id = ?");
        $stmt->execute([$customerId]);
        $addresses = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $addresses[$row['id']] = $row;
        }
        return $addresses;
    }

    public function addAddress($customerId, $street, $city, $country_code)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("INSERT INTO customer_address (customer_id, street, city, country_code) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$customerId, $street, $city, $country_code]);
    }

    public function updateAddress($addressId, $street, $city, $country_code)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("UPDATE customer_address SET street = ?, city = ?, country_code = ? WHERE id = ?");
        return $stmt->execute([$street, $city, $country_code, $addressId]);
    }

    public function deleteAddress($addressId)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("DELETE FROM customer_address WHERE id = ?");
        return $stmt->execute([$addressId]);
    }

    public function updateCustomer($customerId, $data)
    {
        $db = $this->dbh;
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $customerId;
        $sql = "UPDATE customers SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public function getCustomersWithAddresses($limit, $offset)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("SELECT * FROM customers LIMIT ? OFFSET ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($customers as &$customer) {
            $customer['address'] = [];
            $addrStmt = $db->prepare("SELECT street, city, country_code FROM customer_address WHERE customer_id = ?");
            $addrStmt->execute([$customer['id']]);
            while ($row = $addrStmt->fetch(PDO::FETCH_ASSOC)) {
                $customer['address'][] = $row['street'] . ', ' . $row['city'] . ', ' . $row['country_code'];
            }
        }
        return $customers;
    }

    public function countCustomers()
    {
        $db = $this->dbh;
        $stmt = $db->query("SELECT COUNT(*) FROM customers");
        return $stmt->fetchColumn();
    }

    public function deleteCustomer($customerId)
    {
        $db = $this->dbh;
        $db->prepare("DELETE FROM customer_address WHERE customer_id = ?")->execute([$customerId]);
        $stmt = $db->prepare("DELETE FROM customers WHERE id = ?");
        return $stmt->execute([$customerId]);
    }

    public function addCustomer($data)
{
    $db = $this->dbh;
    error_log("Dữ liệu đầu vào addCustomer: " . print_r($data, true)); // Log dữ liệu đầu vào
    $stmt = $db->prepare("INSERT INTO customers (first_name, last_name, email, phone_number, password) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([
        $data['first_name'] ?? null,
        $data['last_name'] ?? null,
        $data['email'] ?? null,
        $data['phone_number'] ?? null,
        $data['password'] ?? null
    ]);

    if ($result === false) {
        $error = $stmt->errorInfo();
        error_log("Lỗi khi thêm khách hàng: " . print_r($error, true));
        return false;
    }

    $lastId = $db->lastInsertId();
    error_log("ID khách hàng vừa thêm: " . $lastId);
    return $lastId;
}

    public function saveResetToken($email, $token)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("UPDATE customers SET reset_token = ? WHERE email = ?");
        return $stmt->execute([$token, $email]);
    }

    public function getCustomerByToken($token)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("SELECT * FROM customers WHERE reset_token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($email, $password)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("UPDATE customers SET password = ? WHERE email = ?");
        $stmt->execute([$password, $email]);
        return $stmt->rowCount() > 0;
    }

    public function clearResetToken($email)
    {
        $db = $this->dbh;
        $stmt = $db->prepare("UPDATE customers SET reset_token = NULL WHERE email = ?");
        return $stmt->execute([$email]);
    }
}
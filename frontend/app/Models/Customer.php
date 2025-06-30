<?php
require_once __DIR__ . '/../../config/Database.php';

class Customer extends Database
{
    public function __construct()
    {
        parent::__construct();
        if ($this->getError()) {
            die($this->getError());
        }
    }
    public function getCustomerById($id)
    {
        $db = $this->getDbh();
        $stmt = $db->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function emailExists($email)
    {
        $db = $this->getDbh();
        $stmt = $db->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }
    public function getCustomerAddresses($customerId)
    {
        $db = $this->getDbh();
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
        $db = $this->getDbh();
        $stmt = $db->prepare("INSERT INTO customer_address (customer_id, street, city, country_code) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$customerId, $street, $city, $country_code]);
    }

    public function updateAddress($addressId, $street, $city, $country_code)
    {
        $db = $this->getDbh();
        $stmt = $db->prepare("UPDATE customer_address SET street = ?, city = ?, country_code = ? WHERE id = ?");
        return $stmt->execute([$street, $city, $country_code, $addressId]);
    }

    public function deleteAddress($addressId)
    {
        $db = $this->getDbh();
        $stmt = $db->prepare("DELETE FROM customer_address WHERE id = ?");
        return $stmt->execute([$addressId]);
    }

    public function updateCustomer($customerId, $data)
    {
        $db = $this->getDbh();
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
        $db = $this->getDbh();
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
        $db = $this->getDbh();
        $stmt = $db->query("SELECT COUNT(*) FROM customers");
        return $stmt->fetchColumn();
    }

    public function deleteCustomer($customerId)
    {
        $db = $this->getDbh();
        $db->prepare("DELETE FROM customer_address WHERE customer_id = ?")->execute([$customerId]);
        $stmt = $db->prepare("DELETE FROM customers WHERE id = ?");
        return $stmt->execute([$customerId]);
    }

    public function addCustomer($data)
    {
        $db = $this->getDbh();
        error_log("Dữ liệu đầu vào addCustomer: " . print_r($data, true));
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

  public function getCustomerByEmail($email)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCustomerByToken($token)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM customers WHERE reset_token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveResetToken($email, $token)
    {
        $stmt = $this->dbh->prepare("UPDATE customers SET reset_token = ? WHERE email = ?");
        return $stmt->execute([$token, $email]);
    }

    public function updatePassword($email, $password)
    {
        $stmt = $this->dbh->prepare("UPDATE customers SET password = ?, reset_token = NULL WHERE email = ?");
        return $stmt->execute([$password, $email]);
    }

    public function clearResetToken($email)
    {
        $stmt = $this->dbh->prepare("UPDATE customers SET reset_token = NULL WHERE email = ?");
        return $stmt->execute([$email]);
    }
}
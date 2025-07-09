<?php
require_once __DIR__ . '/../../config/Database.php';

class Order
{
    private $dbh;

    public function __construct()
    {
        $database = new Database();
        $this->dbh = $database->getDbh();
        if ($this->dbh === null) {
            throw new Exception("Không thể kết nối đến cơ sở dữ liệu.");
        }
    }
    public function assignOrderToCustomer($order_id, $customer_id)
    {
        $stmt = $this->dbh->prepare("UPDATE orders SET customer_id = :customer_id WHERE id = :order_id");
        return $stmt->execute([
            ':customer_id' => $customer_id,
            ':order_id' => $order_id
        ]);
    }
    public function getOrderById($order_id)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($order_id)
    {
        $stmt = $this->dbh->prepare("
            SELECT oi.*, p.product_name 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrders($customerId = null)
    {
        if ($customerId) {
            $stmt = $this->dbh->prepare("
            SELECT * FROM orders 
            WHERE customer_id = :customer_id 
            ORDER BY id DESC
        ");
            $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
        } else {
            // Trường hợp không có customer_id (ví dụ admin xem toàn bộ), nhưng loại bỏ guest
            $stmt = $this->dbh->prepare("
            SELECT * FROM orders 
            WHERE customer_id IS NOT NULL 
            ORDER BY id DESC
        ");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getStatusList()
    {
        $stmt = $this->dbh->prepare("SELECT status, label FROM order_status ORDER BY status_id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [1 => 'Pending', ...]
    }

    public function updateStatus($orderId, $status)
    {
        $stmt = $this->dbh->prepare("UPDATE orders SET status = :status_id WHERE id = :id");
        return $stmt->execute([
            ':status_id' => $status,
            ':id' => $orderId
        ]);
    }

    public function cancelOrder($orderId)
    {
        return $this->updateStatus($orderId, 4); // 4 = Cancelled
    }

    public function createOrder($data) {
        $address = isset($data['customer_address']) && is_array(json_decode($data['customer_address'], true))
            ? $data['customer_address']
            : json_encode(['street' => '', 'city' => '', 'country_code' => '', 'detail' => '']);

        $stmt = $this->dbh->prepare("INSERT INTO orders (customer_id, customer_name, customer_email, customer_phone, address, shipping_method, payment_method, total, status) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['customer_id'], $data['customer_name'], $data['customer_email'],
            $data['customer_phone'], $address, $data['shipping_method'], $data['payment_method'],
            $data['total'], $data['status_id']
        ]);
        return $this->dbh->lastInsertId();
    }

    public function addOrderItem($order_id, $product_id, $quantity, $price) {
        $stmt = $this->dbh->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$order_id, $product_id, $quantity, $price]);
    }

    public function decreaseProductStock($product_id, $qty) {
        $stmt = $this->dbh->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?");
        return $stmt->execute([$qty, $product_id, $qty]);
    }
}
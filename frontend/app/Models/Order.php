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
            $stmt = $this->dbh->prepare("SELECT * FROM orders WHERE customer_id = :customer_id ORDER BY created_at DESC");
            $stmt->bindParam(':customer_id', $customerId);
        } else {
            $stmt = $this->dbh->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getStatusList()
    {
        $stmt = $this->dbh->prepare("SELECT status_id, label FROM order_status ORDER BY status_id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [1 => 'Pending', ...]
    }

    public function updateStatus($orderId, $statusId)
    {
        $stmt = $this->dbh->prepare("UPDATE orders SET status_id = :status_id WHERE id = :id");
        return $stmt->execute([
            ':status_id' => $statusId,
            ':id' => $orderId
        ]);
    }

    public function cancelOrder($orderId)
    {
        return $this->updateStatus($orderId, 4); // 4 = Cancelled
    }
    public function createOrder($data) {
        $stmt = $this->dbh->prepare("INSERT INTO orders (customer_id, customer_name, customer_email, customer_phone, customer_address, total_price, shipping_method, payment_method, status_id) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['customer_id'], $data['customer_name'], $data['customer_email'],
            $data['customer_phone'], $data['customer_address'], $data['total_price'],
            $data['shipping_method'], $data['payment_method'], $data['status_id']
        ]);
        return $this->dbh->lastInsertId();
    }

    public function addOrderItem($order_id, $product_id, $quantity, $price) {
        $stmt = $this->dbh->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$order_id, $product_id, $quantity, $price]);
    }

    public function decreaseProductStock($product_id, $qty) {
        $stmt = $this->dbh->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        return $stmt->execute([$qty, $product_id]);
    }

}

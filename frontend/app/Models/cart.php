<?php
require_once __DIR__ . '/../../config/Database.php';

class Cart
{
    protected $dbh;

    public function __construct()
    {
        $database = new Database();
        $this->dbh = $database->getDbh();
        if ($this->dbh === null) {
            throw new Exception("Không thể kết nối đến cơ sở dữ liệu.");
        }
    }

    public function addToCart($productId, $customerId)
    {
        $stmt = $this->dbh->prepare("INSERT INTO cart (customer_id, product_id, quantity) VALUES (?, ?, 1) 
                                ON DUPLICATE KEY UPDATE quantity = quantity + 1");
        return $stmt->execute([$customerId, $productId]);
    }

    public function getCartItems($customerId)
    {
        $stmt = $this->dbh->prepare("SELECT c.product_id, c.quantity, p.product_name, p.price, p.image 
                                    FROM cart c 
                                    JOIN products p ON c.product_id = p.id 
                                    WHERE c.customer_id = ?");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCartItem($customerId, $productId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($customerId, $productId);
        }

        $stmt = $this->dbh->prepare("UPDATE cart SET quantity = ? WHERE customer_id = ? AND product_id = ?");
        return $stmt->execute([$quantity, $customerId, $productId]);
    }

    public function removeFromCart($customerId, $productId)
    {
        $stmt = $this->dbh->prepare("DELETE FROM cart WHERE customer_id = ? AND product_id = ?");
        return $stmt->execute([$customerId, $productId]);
    }
}
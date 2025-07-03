<?php
require_once __DIR__ . '/../../config/Database.php';

class Cart
{
    protected $dbh;

    public function __construct()
    {
        $database = new Database();
        $this->dbh = $database->getDbh(); // Lấy đối tượng PDO từ Database
        if ($this->dbh === null) {
            throw new Exception("Không thể kết nối đến cơ sở dữ liệu.");
        }
    }

    public function addToCart($customerId, $productId, $quantity = 1)
    {
        try {
            error_log("Adding to cart: customerId=$customerId, productId=$productId, quantity=$quantity");
            $stmt = $this->dbh->prepare("INSERT INTO cart (customer_id, product_id, quantity) VALUES (?, ?, ?) 
                                    ON DUPLICATE KEY UPDATE quantity = quantity + ?");
            $result = $stmt->execute([$customerId, $productId, $quantity, $quantity]);
            if (!$result) {
                error_log("Failed to add to cart: " . print_r($stmt->errorInfo(), true));
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Database error in addToCart: " . $e->getMessage());
            return false;
        }
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
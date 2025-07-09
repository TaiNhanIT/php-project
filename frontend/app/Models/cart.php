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

    public function addToCart($customerId, $customerSession, $productId, $quantity = 1)
    {
        try {
            if ($customerId !== null) {
                $stmt = $this->dbh->prepare("
                INSERT INTO cart (customer_id, customer_session, product_id, quantity) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
            ");
                return $stmt->execute([$customerId, $customerSession, $productId, $quantity]);
            } else {
                $stmt = $this->dbh->prepare("
                INSERT INTO cart (customer_session, product_id, quantity) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
            ");
                return $stmt->execute([$customerSession, $productId, $quantity]);
            }
        } catch (PDOException $e) {
            error_log("Database error in addToCart: " . $e->getMessage());
            return false;
        }
    }


    public function getCartItems($customerId = null, $customerSession = null)
    {
        try {
            if ($customerId) {
                $stmt = $this->dbh->prepare("SELECT c.product_id, c.quantity, p.product_name, p.price, p.image 
                                         FROM cart c 
                                         JOIN products p ON c.product_id = p.id 
                                         WHERE c.customer_id = ?");
                $stmt->execute([$customerId]);
            } elseif ($customerSession) {
                $stmt = $this->dbh->prepare("SELECT c.product_id, c.quantity, p.product_name, p.price, p.image 
                                 FROM cart c 
                                 JOIN products p ON c.product_id = p.id 
                                 WHERE c.customer_session = ?");
                $stmt->execute([$customerSession]);
            }
            else {
                return [];
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in getCartItems: " . $e->getMessage());
            return [];
        }
    }

    public function updateCartItem($customerId, $customerSession, $productId, $quantity)
    {
        try {
            if ($customerId) {
                $stmt = $this->dbh->prepare("UPDATE cart SET quantity = ? WHERE customer_id = ? AND product_id = ?");
                return $stmt->execute([$quantity, $customerId, $productId]);
            } else {
                $stmt = $this->dbh->prepare("UPDATE cart SET quantity = ? WHERE customer_session = ? AND product_id = ?");
                return $stmt->execute([$quantity, $customerSession, $productId]);
            }
        } catch (PDOException $e) {
            error_log("Error in updateCartItem: " . $e->getMessage());
            return false;
        }
    }

    public function removeFromCart($customerId, $customerSession, $productId)
    {
        try {
            if ($customerId) {
                $stmt = $this->dbh->prepare("DELETE FROM cart WHERE customer_id = ? AND product_id = ?");
                return $stmt->execute([$customerId, $productId]);
            } else {
                $stmt = $this->dbh->prepare("DELETE FROM cart WHERE customer_session = ? AND product_id = ?");
                return $stmt->execute([$customerSession, $productId]);
            }
        } catch (PDOException $e) {
            error_log("Error in removeFromCart: " . $e->getMessage());
            return false;
        }
    }
    public function clearCart($customerId, $customerSession = null)
    {
        if ($customerId !== null) {
            $stmt = $this->dbh->prepare("DELETE FROM cart WHERE customer_id = ?");
            return $stmt->execute([$customerId]);
        } elseif ($customerSession !== null) {
            $stmt = $this->dbh->prepare("DELETE FROM cart WHERE customer_session = ?");
            return $stmt->execute([$customerSession]);
        }
        return false;
    }
}

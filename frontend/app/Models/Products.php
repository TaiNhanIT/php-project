<?php
require_once __DIR__ . '/../../config/Database.php';

class Products extends Database
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

    public function getProductById($id)
    {
        $stmt = $this->dbh->prepare("SELECT p.id, p.product_name, p.price, p.description, p.stock_quantity, p.image 
                                    FROM products p 
                                    WHERE p.id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $product['category_name'] = $this->getCategoryName($id);
        }
        return $product ?: null;
    }

    public function getAllProducts()
    {
        $stmt = $this->dbh->prepare("SELECT p.id, p.product_name, p.price, p.description, p.stock_quantity, p.image 
                                    FROM products p 
                                    JOIN product_categories pc ON p.id = pc.product_id");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as &$product) {
            $product['category_name'] = $this->getCategoryName($product['id']);
        }
        return $products;
    }

    public function getProductsByCategory($categoryId)
    {
        $stmt = $this->dbh->prepare("SELECT p.id, p.product_name, p.price, p.description, p.stock_quantity, p.image 
                                    FROM products p 
                                    JOIN product_categories pc ON p.id = pc.product_id 
                                    WHERE pc.category_id = ?");
        $stmt->execute([$categoryId]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as &$product) {
            $product['category_name'] = $this->getCategoryName($product['id']);
        }
        return $products;
    }

    private function getCategoryName($productId)
    {
        $stmt = $this->dbh->prepare("SELECT c.name 
                                    FROM categories c 
                                    JOIN product_categories pc ON c.id = pc.category_id 
                                    WHERE pc.product_id = ? 
                                    LIMIT 1");
        $stmt->execute([$productId]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category ? $category['name'] : 'Chưa có danh mục';
    }
}
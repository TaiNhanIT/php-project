<?php
require_once __DIR__ . '/../../config/database.php';

class Product extends Database
{
    public function __construct()
    {
        parent::__construct(); // initialize $this->dbh
    }

    public function addProduct($data, $files)
    {
        // Use $this->dbh instead of $this->connect()
        $stmt = $this->dbh->prepare("INSERT INTO products (product_name, price, description, stock_quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['product_name'],
            $data['price'],
            $data['description'],
            $data['stock_quantity']
        ]);
        $product_id = $this->dbh->lastInsertId();

        if (!empty($files['image']['name'])) {
            $imgName = uniqid() . '_' . basename($files['image']['name']);
            move_uploaded_file($files['image']['tmp_name'], 'images/' . $imgName);
            $this->dbh->prepare("UPDATE products SET image = ? WHERE id = ?")->execute([$imgName, $product_id]);
        }
        return $product_id;
    }

    public function addProductCategory($product_id, $category_id)
    {
        $stmt = $this->dbh->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
        $stmt->execute([$product_id, $category_id]);
    }

    public function getByCategory($category_id)
    {
        $stmt = $this->dbh->prepare("
            SELECT p.* FROM products p
            JOIN product_categories pc ON p.id = pc.product_id
            WHERE pc.category_id = ?
            ORDER BY p.id DESC
        ");
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->dbh->query("SELECT * FROM products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductCategories($product_id)
    {
        $stmt = $this->dbh->prepare("SELECT category_id FROM product_categories WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProduct($id, $data, $files)
    {
        $stmt = $this->dbh->prepare("UPDATE products SET product_name = ?, price = ?, description = ?, stock_quantity = ? WHERE id = ?");
        $stmt->execute([
            $data['product_name'],
            $data['price'],
            $data['description'],
            $data['stock_quantity'],
            $id
        ]);

        if (!empty($files['image']['name'])) {
            $imgName = uniqid() . '_' . basename($files['image']['name']);
            move_uploaded_file($files['image']['tmp_name'], 'images/' . $imgName);
            $this->dbh->prepare("UPDATE products SET image = ? WHERE id = ?")->execute([$imgName, $id]);
        }

        $this->dbh->prepare("DELETE FROM product_categories WHERE product_id = ?")->execute([$id]);

        if (!empty($data['category_ids'])) {
            foreach ($data['category_ids'] as $category_id) {
                $this->addProductCategory($id, $category_id);
            }
        }
    }

    public function deleteProduct($id)
    {
        $this->dbh->prepare("DELETE FROM product_categories WHERE product_id = ?")->execute([$id]);
        $this->dbh->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    }
}

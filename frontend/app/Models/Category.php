<?php
require_once __DIR__ . '/../../config/database.php';

class Category extends Database
{
    public function __construct()
    {
        parent::__construct(); // initialize $this->dbh
    }

    // Lấy tất cả danh mục
    public function getAll()
    {
        $stmt = $this->dbh->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh mục theo id 
    public function getById($id)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

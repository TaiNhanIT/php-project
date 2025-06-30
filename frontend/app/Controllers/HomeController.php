<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Products.php';
require_once __DIR__ . '/../Models/Category.php';

class HomeController extends Controller {
    private $categoryModel;
    private $productModel;

    public function __construct()
    {
        $this->categoryModel = new Category(); // Category tự khởi tạo Database theo file bạn cung cấp
        $this->productModel = new Products(); // Products tự khởi tạo kết nối
    }

    public function index()
    {
        try {
            $categories = $this->categoryModel->getAll();
            $productsByCategory = [];
            foreach ($categories as $category) {
                $productsByCategory[] = $this->productModel->getProductsByCategory($category['id']);
            }

            $this->view('home', [
                'productsByCategory' => $productsByCategory,
                'categories' => $categories
            ]);
        } catch (Exception $e) {
            die('Lỗi khi tải trang chủ: ' . $e->getMessage());
        }
    }
}
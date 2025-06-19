<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Products.php';
require_once __DIR__ . '/../Models/Category.php';

class HomeController extends Controller {
    private $categoryModel;
    private $productModel;

    public function __construct()
    {
        $db = new Database(); 
        $this->categoryModel = new Category($db);
        $this->productModel = new Product($db);
    }

    public function index()
    {
        try {
            // Lấy danh sách tất cả danh mục
            $categories = $this->categoryModel->getAll();

            // Khởi tạo mảng để lưu sản phẩm theo danh mục
            $productsByCategory = [];

            // Lấy sản phẩm cho mỗi danh mục
            foreach ($categories as $cat) {
                $categoryId = $cat['id'];
                $productsByCategory[$categoryId] = $this->productModel->getByCategory($categoryId) ?: [];
            }

            // Truyền dữ liệu vào view
            $this->view('home', [
                'categories' => $categories,
                'productsByCategory' => $productsByCategory
            ]);
        } catch (Exception $e) {
            die('Lỗi khi tải trang chủ: ' . $e->getMessage());
        }
    }
}

<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Products.php';

class ProductController extends Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function detail($id = '')
    {
        if (empty($id) || !is_numeric($id)) {
            $this->view('error', ['error' => 'Sản phẩm không tồn tại.']);
            return;
        }

        $productModel = new Products();
        $product = $productModel->getProductById($id);

        if (!$product) {
            $this->view('error', ['error' => 'Sản phẩm không tìm thấy.']);
            return;
        }

        $customerId = $_SESSION['customer_id'] ?? null;

        $this->view('products/detail', [
            'product'     => $product,
            'message'     => '',
            'customerId'  => $customerId
        ]);
    }

    public function productDetail($id = '')
    {
        if (empty($id) || !is_numeric($id)) {
            $error = 'Sản phẩm không tồn tại.';
            $this->view('error', ['error' => $error]);
            return;
        }

        $productModel = new Products();
        $product = $productModel->getProductById($id);

        if (!$product) {
            $error = 'Sản phẩm không tìm thấy.';
            $this->view('error', ['error' => $error]);
            return;
        }

        $customerId = $_SESSION['customer_id'] ?? null;
        $message = '';

        $this->view('products/detail', [
            'product' => $product,
            'message' => $message,
            'customerId' => $customerId
        ]);
    }
}
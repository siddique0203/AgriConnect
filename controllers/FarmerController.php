<?php
require_once __DIR__ . "/../models/db.php";
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/ProductModel.php";
require_once __DIR__ . "/../models/OrderModel.php";
require_once __DIR__ . "/../models/PaymentModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";
//require_once "../../models/OrderModel.php";
//require_once "../../models/PaymentModel.php";
//require_once "../../models/ReviewModel.php";

class FarmerController {
    private $conn;
    private $userModel;
    private $productModel;
    private $orderModel;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        $this->userModel    = new UserModel($this->conn);
        $this->productModel = new ProductModel($this->conn);
        $this->orderModel   = new OrderModel($this->conn);
        $this->paymentModel = new PaymentModel($this->conn);
        $this->reviewModel  = new ReviewModel($this->conn);
    }

    // Handle add/delete/search actions
    public function handleActions() {
    // --- Add Product ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category = $_POST['category'];
        $farmerId = $_SESSION['user_id'];

        // Handle image upload
        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "../../uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $imageName = time() . "_" . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
        }

        // Generate unique product ID
        $productId = $this->productModel->generateProductId();

        $this->productModel->createProduct(
            $productId,
            $farmerId,
            $name,
            $description,
            $price,
            $stock,
            $imageName,
            $category
        );

        header("Location: farmerDashboard.php?section=products");
        exit;
    }

    // --- Delete Product ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_product') {
        $this->productModel->deleteProduct($_POST['product_id']);
        header("Location: farmerDashboard.php?section=products");
        exit;
    }

    // --- Search Product ---
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'search_product') {
        $keyword = $_GET['keyword'] ?? '';
        $products = $this->productModel->searchProductsForFarmer($_SESSION['user_id'], $keyword);
        include "../../views/farmer/sections/products.php";
        exit;
    }

    //update profile
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        $currentUser = $this->userModel->findUserById($_SESSION['user_id']);

        $name    = !empty($_POST['name']) ? $_POST['name'] : $currentUser['name'];
        $email   = !empty($_POST['email']) ? $_POST['email'] : $currentUser['email'];
        $phone   = !empty($_POST['phone']) ? $_POST['phone'] : $currentUser['phone'];
        $address = !empty($_POST['address']) ? $_POST['address'] : $currentUser['address'];
        $nid     = isset($_POST['nid']) ? $_POST['nid'] : $currentUser['nid'];

        $this->userModel->updateUser($_SESSION['user_id'], $name, $email, $phone, $address, $nid);

        header("Location: farmerDashboard.php?section=profile&success=1");
        exit;
    }

    // --- Update Order Status ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_order_status') {
        $orderId = $_POST['order_id'];
        $status  = $_POST['status'];

        $this->orderModel->updateOrderStatus($orderId, $status);

        header("Location: farmerDashboard.php?section=orders");
        exit;
    }

}


    // Load sections
    public function loadSection($section) {
        switch ($section) {
            case 'profile':
                $user = $this->userModel->findUserById($_SESSION['user_id']);
                include "../../views/farmer/sections/profile.php";
                break;

            case 'products':
                $products = $this->productModel->getProductsByFarmer($_SESSION['user_id']);
                include "../../views/farmer/sections/products.php";
                break;

            case 'orders':
                $orders = $this->orderModel->getOrdersForFarmer($_SESSION['user_id']);
                include "../../views/farmer/sections/orders.php";
                break;

            case 'payments':
                 $payments = $this->paymentModel->getPaymentsForFarmer($_SESSION['user_id']);
                 include "../../views/farmer/sections/payments.php";
                break;

            case 'feedback':
                 $reviews = $this->reviewModel->getReviewsForFarmer($_SESSION['user_id']);
                 include "../../views/farmer/sections/feedback.php";
                break;

            default:
                echo "<p>Invalid Section</p>";
        }
    }
}
?>

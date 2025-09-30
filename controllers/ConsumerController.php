<?php
// // controllers/ConsumerController.php
// require_once __DIR__ . "/../models/UserModel.php";
// require_once __DIR__ . "/../models/OrderModel.php";
// require_once __DIR__ . "/../models/CartModel.php";
require_once __DIR__ . "/../models/db.php";          
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/ProductModel.php";
require_once __DIR__ . "/../models/OrderModel.php";
require_once __DIR__ . "/../models/CartModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";
require_once __DIR__ . "/../models/PaymentModel.php";


class ConsumerController {
    private $conn;
    private $userModel;
    private $orderModel;
    private $cartModel;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new UserModel($this->conn);
        $this->productModel = new ProductModel($this->conn);
        $this->orderModel = new OrderModel($this->conn);
        $this->cartModel = new CartModel($this->conn);
        $this->reviewModel = new ReviewModel($this->conn);
        $this->paymentModel = new PaymentModel($this->conn);

    }

    // process POST actions
    public function handleActions() {
    if (session_status() === PHP_SESSION_NONE) session_start();

    $action = $_POST['action'] ?? null; 

    // Update profile
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_profile') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../views/login.php");
            exit;
        }

        $currentUser = $this->userModel->findUserById($_SESSION['user_id']);
        if (!$currentUser) {
            header("Location: ../../views/login.php");
            exit;
        }

        $name    = !empty($_POST['name']) ? trim($_POST['name']) : $currentUser['name'];
        $email   = !empty($_POST['email']) ? trim($_POST['email']) : $currentUser['email'];
        $phone   = !empty($_POST['phone']) ? trim($_POST['phone']) : $currentUser['phone'];
        $address = !empty($_POST['address']) ? trim($_POST['address']) : $currentUser['address'];
        $nid     = isset($_POST['nid']) ? trim($_POST['nid']) : $currentUser['nid'];

        $this->userModel->updateUser($_SESSION['user_id'], $name, $email, $phone, $address, $nid);

        header("Location: consumerProfile.php?section=profile&success=1");
        exit;
    }

    // Remove single cart item
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'removeFromCart') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../views/login.php");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $cartId = $_POST['cart_id'] ?? null;

        if ($cartId) {
            $this->cartModel->removeItem($cartId, $userId);
        }

        header("Location: ../views/consumer/consumerDashboard.php?section=cart");
        exit;
    }
}

    // load view sections
    public function loadSection($section) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        switch ($section) {
            case 'profile':
                $user = $this->userModel->findUserById($_SESSION['user_id']);
                include __DIR__ . "/../views/consumer/sections/profileDetails.php";
                break;

            case 'orders':
                $orders = $this->orderModel->getOrdersForUser($_SESSION['user_id']);
                include __DIR__ . "/../views/consumer/sections/orderDetails.php";
                break;

            case 'cart':
                $cart = $this->cartModel->getCartForUser($_SESSION['user_id']);
                include __DIR__ . "/../views/consumer/sections/cartList.php";
                break;

            default:
                echo "<p>Invalid Section</p>";
        }
    }
    public function getProducts() {
        return $this->productModel->getAllProducts();
    }
    public function getFilteredProducts($filters = []) {
        if (!empty($filters)) {
            $category = $filters['category'] ?? '';
            $minPrice = $filters['minPrice'] ?? '';
            $maxPrice = $filters['maxPrice'] ?? '';
            return $this->productModel->filterProducts($category, $minPrice, $maxPrice);
        }
        return $this->productModel->getAllProducts();
    }

    public function getProductDetails($productId) {
        return $this->productModel->getProductById($productId);
    }

    public function getReviews($farmerId) {
        return $this->reviewModel->getReviewsForFarmer($farmerId);
    }

    public function getCart($userId) {
        return $this->cartModel->getCartForUser($userId);
    }
    public function getReviewsForProduct($productId) {
        return $this->reviewModel->getReviewsForProduct($productId);
    }

    public function addReview($productId, $userId, $rating, $comment) {
    return $this->reviewModel->addReview($productId, $userId, $rating, $comment);
    }

    public function addToCart($userId, $productId, $quantity) {
    return $this->cartModel->addToCart($userId, $productId, $quantity);
    }   
     public function getCartItems($userId) {
        return $this->cartModel->getCartForUser($userId);
    }

    public function getCartTotal($userId) {
        return $this->cartModel->getTotalPrice($userId);
    }

    public function checkout($userId) {
        $cartItems = $this->cartModel->getCartForUser($userId);
        $total = $this->cartModel->getTotalPrice($userId);

        if (empty($cartItems)) return false;

        $orderId = $this->orderModel->createOrder($userId, $cartItems, $total);

        // Clear cart after order
        $this->cartModel->clearCart($userId);

        return $orderId;
    }

    public function placeOrder($userId, $paymentMethod) {
    $cartItems = $this->cartModel->getCartForUser($userId);
    $total = $this->cartModel->getTotalPrice($userId);

    if (empty($cartItems)) return false;

    // 1️ Create order + order items
    $orderId = $this->orderModel->createOrder($userId, $cartItems, $total);
    if (!$orderId) return false;

    // 2️ Create payment using PaymentModel
    $this->paymentModel->createPayment($orderId, $total, $paymentMethod);

    // 3️Clear cart
    $this->cartModel->clearCart($userId);

    return $orderId;
}





}
$controller = new ConsumerController($conn);
$controller->handleActions();
?>

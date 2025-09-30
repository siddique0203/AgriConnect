<?php
require_once __DIR__ . "/../models/db.php";
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/ProductModel.php";
require_once __DIR__ . "/../models/OrderModel.php";
require_once __DIR__ . "/../models/PaymentModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";
require_once __DIR__ . "/../models/ContactModel.php";
require_once __DIR__ . '/../models/AdminModel.php';


class AdminController {
    private $conn;
    private $userModel;
    private $productModel;
    private $orderModel;
    private $paymentModel;
    private $reviewModel;
    private $contactModel;
    private $adminModel;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        $this->userModel    = new UserModel($this->conn);
        $this->productModel = new ProductModel($this->conn);
        $this->orderModel   = new OrderModel($this->conn);
        $this->paymentModel = new PaymentModel($this->conn);
        $this->reviewModel  = new ReviewModel($this->conn);
        $this->contactModel = new ContactModel($this->conn);
        $this->adminModel = new AdminModel($this->conn);
    }

    public function handleActions() {
        // --- User search / delete / update ---
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'search_user') {
            $type = $_GET['user_type'] ?? '';
            $keyword = $_GET['keyword'] ?? '';
            $users = $this->userModel->searchUsers($type, $keyword);
            include __DIR__ . "/../views/admin/sections/users.php";
            exit;
        }
        //add admin
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_admin') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $success = $this->adminModel->addAdmin($name, $email, $password);

            if ($success) {
                header("Location: adminDashboard.php?section=admins&success=1");
            } else {
                header("Location: adminDashboard.php?section=admins&error=email_exists");
            }
            exit;
        }

        // --- Delete Admin with super admin password ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_admin') {
            $adminId = $_POST['admin_id'];
            $superPass = $_POST['super_pass'];
            $result = $this->adminModel->deleteAdmin($adminId, $superPass);

            if ($result === true) {
                header("Location: adminDashboard.php?section=admins&success=delete");
            } elseif ($result === "wrong_super_pass") {
                header("Location: adminDashboard.php?section=admins&error=wrong_super_pass");
            } elseif ($result === "last_admin") {
                header("Location: adminDashboard.php?section=admins&error=last_admin");
            } else {
                header("Location: adminDashboard.php?section=admins&error=delete_fail");
            }
            exit;
        }



        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
            $this->userModel->deleteUser($_POST['user_id']);
            header("Location: adminDashboard.php?section=users");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_user') {
            $id = $_POST['user_id'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $nid = $_POST['nid'] ?? null;
            $this->userModel->updateUser($id, $name, null, $phone, $address, $nid);
            header("Location: adminDashboard.php?section=users&success=1");
            exit;
        }

        // --- Product search / delete / update stock ---
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'search_product') {
            $category = $_GET['category'] ?? '';
            $keyword = $_GET['keyword'] ?? '';
            $products = $this->productModel->searchProductsAdmin($category, $keyword);
            include __DIR__ . "/../views/admin/sections/products.php";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_product') {
            $this->productModel->deleteProduct($_POST['product_id']);
            header("Location: adminDashboard.php?section=products");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_stock') {
            $productId = $_POST['product_id'];
            $stock = intval($_POST['stock']);
            $this->productModel->overrideStock($productId, $stock);
            header("Location: adminDashboard.php?section=products");
            exit;
        }

        // --- Orders management ---
       
        // --- Orders management ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_order_status') {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];
            $this->orderModel->updateOrderStatus($orderId, $status);
            header("Location: adminDashboard.php?section=orders");
            exit;
        }

        // --- View cart and order history for a specific user ---
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'view_cart') {
            $userId = $_GET['user_id'] ?? '';

            if(!empty($userId)) {
                // Fetch orders and cart for that user
                $orders = $this->orderModel->getOrdersForUser($userId);
                $cart = $this->orderModel->getCartByUser($userId);
            } else {
                // No user_id entered, show all orders and no cart
                $orders = $this->orderModel->getAllOrders();
                $cart = [];
            }

            include __DIR__ . "/../views/admin/sections/orders.php";
            exit;
        }

        // --- Update cart quantity ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_cart') {
            $cartId = $_POST['cart_id'];
            $quantity = intval($_POST['quantity']);
            $this->orderModel->updateCartQuantity($cartId, $quantity);
            header("Location: adminDashboard.php?section=orders");
            exit;
        }

        // --- Delete cart item ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_cart') {
            $cartId = $_POST['cart_id'];
            $this->orderModel->deleteCartItem($cartId);
            header("Location: adminDashboard.php?section=orders");
            exit;
        }



        // --- Payments ---
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'search_payment') {
            $method = $_GET['method'] ?? '';
            $payments = $this->paymentModel->searchPaymentsByMethod($method);
            include __DIR__ . "/../views/admin/sections/payments.php";
            exit;
        }

        // --- Reviews ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_review') {
            $this->reviewModel->deleteReview($_POST['review_id']);
            header("Location: adminDashboard.php?section=reviews");
            exit;
        }

        // --- Queries ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'respond_query') {
            $queryId = $_POST['query_id'];
            $response = $_POST['response'];
            $status = $_POST['status'];
            $this->contactModel->respondToQuery($queryId, $response, $status);
            header("Location: adminDashboard.php?section=queries");
            exit;
        }
    }

    public function loadSection($section) {
        switch($section) {
            case 'users':
                $userType = $_GET['user_type'] ?? 'All';
                $users = $this->userModel->getUsersByType($userType);
                include __DIR__ . "/../views/admin/sections/users.php";
                break;

            case 'products':
            $category = $_GET['category'] ?? '';
            $keyword = $_GET['keyword'] ?? '';
            $products = $this->productModel->searchProductsAdmin($category, $keyword);
            include __DIR__ . "/../views/admin/sections/products.php";
            break;


            case 'orders':
                $orders = $this->orderModel->getAllOrders();
                include __DIR__ . "/../views/admin/sections/orders.php";
                break;

            case 'payments':
                $payments = $this->paymentModel->getAllPayments();
                include __DIR__ . "/../views/admin/sections/payments.php";
                break;

            case 'reviews':
                $reviews = $this->reviewModel->getAllReviews();
                include __DIR__ . "/../views/admin/sections/reviews.php";
                break;

            case 'queries':
                $status = $_GET['status'] ?? '';
                $queries = $this->contactModel->getAllQueries($status);
                include __DIR__ . "/../views/admin/sections/queries.php";
                break;
            case 'admins':
            $admins = $this->adminModel->getAllAdmins();
            include __DIR__ . "/../views/admin/sections/admins.php";
            break;





            default:
                echo "<p>Invalid Section</p>";
        }
    }
}
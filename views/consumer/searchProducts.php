<?php
session_start();
require_once "../../models/db.php";
require_once "../../controllers/ConsumerController.php";

if (!isset($_SESSION['user_id'], $_COOKIE['logged_in']) || $_SESSION['user_type'] !== 'consumer') {
    exit("Unauthorized");
}

$controller = new ConsumerController($conn);
$keyword = $_GET['keyword'] ?? '';
$searchMode = isset($_GET['search']); // If search button clicked

$products = $controller->getFilteredProducts(['category'=>'','minPrice'=>'','maxPrice'=>'']);

$products = array_filter($products, function($p) use ($keyword) {
    return stripos($p['name'], $keyword) !== false;
});

if($searchMode) {
    // Return full product cards
    if(!empty($products)) {
        foreach($products as $product) {
            echo '<div class="product-card">
                    <img src="../../uploads/'.htmlspecialchars($product['image']).'" alt="'.htmlspecialchars($product['name']).'">
                    <div class="product-info">
                        <h3>'.htmlspecialchars($product['name']).'</h3>
                        <p>'.htmlspecialchars(substr($product['description'],0,50)).'...</p>
                        <p class="price">৳ '.number_format($product['price'],2).'</p>
                        <a href="productDetails.php?id='.$product['product_id'].'">View</a>
                    </div>
                  </div>';
        }
    } else {
        echo "<p>No products found.</p>";
    }
} else {
    // Return suggestions
    if(!empty($products)) {
        foreach($products as $product) {
            echo '<div class="suggestion-item">'.htmlspecialchars($product['name']).'</div>';
        }
    }
}
?>

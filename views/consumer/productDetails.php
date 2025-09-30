<?php
session_start();
require_once "../../models/db.php";
require_once "../../controllers/ConsumerController.php";

if (!isset($_SESSION['user_id'], $_COOKIE['logged_in']) || $_SESSION['user_type'] !== 'consumer') {
    header("Location: ../../views/login.php");
    exit;
}

$controller = new ConsumerController($conn);

if (!isset($_GET['id'])) {
    echo "Product not found!";
    exit;
}

$productId = $_GET['id'];
$product = $controller->getProductDetails($productId);
$reviews = $controller->getReviewsForProduct($productId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($product['name']); ?></title>
<link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" crossorigin="anonymous" />
</head>
<body>
<header>
<nav>
    <div class="nav-logo"><h3 class="nav-title"><span>Agri</span>Connect</h3></div>
    <ul>
        <li><a href="../../index.php">Home</a></li>
        <li><a href="consumerDashboard.php">Products</a></li>
        <li><a href="consumerProfile.php"><?php echo htmlspecialchars($_SESSION['user_name']); ?></a></li>
    </ul>
</nav>
</header>

<main>
<section id="product-details">
    <div class="product-container">
        <div class="product-image">
            <img src="../../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="price"><?php echo number_format($product['price'],2); ?> ৳</p>
            <p><strong>Category:</strong> <?php echo ucfirst($product['category']); ?></p>
            <p><strong>Stock:</strong> <?php echo $product['stock']; ?></p>
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

            <form method="POST" action="../../controllers/AddToCart.php" class="add-to-cart-form">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="quantity-input">
                <button type="submit" class="cart-btn">Add to Cart</button>
            </form>
            <div style="margin-top:15px;">
            <a href="checkout.php" 
              style="display:inline-block; padding:10px 20px; background:#007bff; color:#fff; text-decoration:none; border-radius:5px; font-weight:bold;">
            Checkout
            </a>
</div>
        </div>
    </div>

    <div class="reviews-section">
        <h2>Reviews</h2>
        <form method="POST" action="../../controllers/AddReview.php" class="review-form">
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <label for="rating">Rating:</label>
            <select name="rating" required>
                <option value="">Select</option>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select>
            <label for="comment">Comment:</label>
            <textarea name="comment" rows="3" placeholder="Write your review..." required></textarea>
            <button type="submit" class="cart-btn">Submit Review</button>
        </form>

        <div class="reviews-list">
            <?php if (empty($reviews)): ?>
                <p>No reviews yet. Be the first to review!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <p class="reviewer-name"><?php echo htmlspecialchars($review['customer_name']); ?></p>
                        <p class="review-rating">
                            <?php for($i=0; $i<$review['rating']; $i++): ?>
                                <i class="fa-solid fa-star" style="color:#FFD700;"></i>
                            <?php endfor; ?>
                        </p>
                        <p class="review-comment"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                        <p class="review-date"><?php echo date("d M Y", strtotime($review['created_at'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
</main>
</body>
</html>

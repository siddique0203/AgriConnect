<?php
session_start();
require_once "../../models/db.php";
require_once "../../controllers/ConsumerController.php";

if (!isset($_SESSION['user_id'], $_COOKIE['logged_in']) || $_SESSION['user_type'] !== 'consumer') {
    header("Location: ../../views/login.php");
    exit;
}

$controller = new ConsumerController($conn);

// Filters from GET
$filters = [
    'category' => $_GET['category'] ?? '',
    'minPrice' => $_GET['minPrice'] ?? '',
    'maxPrice' => $_GET['maxPrice'] ?? ''
];

$products = $controller->getFilteredProducts($filters);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products</title>
<link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
<style>
.container { display: flex; gap: 20px; max-height: 100vh-30px; }
.filters { width: 250px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
.products-grid { flex: 1; display: grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap: 20px; }

.product-card { border: 1px solid #ccc; border-radius: 8px; overflow: hidden; padding: 10px; background: #fff; transition: 0.3s; }
.product-card:hover { box-shadow: 0 0 10px rgba(0,0,0,0.2); }
.product-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 5px; }
.product-info { padding: 10px 0; }
.product-info h3 { margin: 0 0 10px; font-size: 18px; }
.product-info p { margin: 5px 0; font-size: 14px; }
.product-info .price { font-weight: bold; color: #2d6a4f; }

.product-card a { display: inline-block; padding: 10px 10px; background: #2d6a4f; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold;width:100%; }

#search-container { width:100%; margin-bottom:20px; position:relative; display:flex; gap:10px; }
#suggestions { position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid #ccc; z-index:10; max-height:200px; overflow-y:auto; display:none; border-radius:6px; }
.suggestion-item { padding:10px; cursor:pointer; border-bottom:1px solid #eee; }
.suggestion-item:hover { background:#f0f0f0; }
</style>
</head>
<body>

<header>
<nav>
    <div class="nav-logo"><h3 class="nav-title"><span>Agri</span>Connect</h3></div>
    <ul>
        <li><a href="../../index.php">Home</a></li>
        <li><a href="consumerDashboard.php">Dashboard</a></li>
        <li><a href="consumerProfile.php"><?php echo htmlspecialchars($_SESSION['user_name']); ?></a></li>
    </ul>
</nav>
</header>

<main style="padding:100px">
    <!-- Search Box -->
    <div id="search-container" style="padding: 20px 100px; display:flex; gap:10px;">
        <input type="text" id="searchInput" placeholder="Search products..." autocomplete="off">
        <button id="searchBtn">Search</button>
        <div id="suggestions"></div>
    </div>
    <div id="optional" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">

        <!-- Filters + Products Grid -->
        <div class="container">
            <!-- Filters Sidebar -->
            <aside class="filters">
                <h3>Filters</h3>
                <form method="GET" action="consumerProducts.php">
                    <label>Category</label>
                    <select name="category">
                        <option value="">All</option>
                        <option value="vegetable" <?php if($filters['category']=='vegetable') echo 'selected'; ?>>Vegetable</option>
                        <option value="fruit" <?php if($filters['category']=='fruit') echo 'selected'; ?>>Fruit</option>
                        <option value="grain" <?php if($filters['category']=='grain') echo 'selected'; ?>>Grain</option>
                        <option value="dairy" <?php if($filters['category']=='dairy') echo 'selected'; ?>>Dairy</option>
                        <option value="meat" <?php if($filters['category']=='meat') echo 'selected'; ?>>Meat</option>
                        <option value="fish" <?php if($filters['category']=='fish') echo 'selected'; ?>>Fish</option>
                        <option value="grocery" <?php if($filters['category']=='grocery') echo 'selected'; ?>>Grocery</option>
                    </select>
    
                    <label>Min Price</label>
                    <input type="number" name="minPrice" value="<?php echo htmlspecialchars($filters['minPrice']); ?>" placeholder="0">
    
                    <label>Max Price</label>
                    <input type="number" name="maxPrice" value="<?php echo htmlspecialchars($filters['maxPrice']); ?>" placeholder="1000">
    
                    <button type="submit" style="margin-top:10px;width:100%;">Apply</button>
                </form>
            </aside>
    
            <!-- Products Grid -->
            <section class="products-grid" id="products">
                <?php if(!empty($products)): ?>
                    <?php foreach($products as $product): ?>
                    <div class="product-card">
                        <img src="../../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($product['description'],0,50)); ?>...</p>
                            <p class="price">TK <?php echo number_format($product['price'],2); ?></p>
                            <a href="productDetails.php?id=<?php echo $product['product_id']; ?>">View</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No products found for this filter.</p>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>


<script>
const searchInput = document.getElementById('searchInput');
const suggestionsDiv = document.getElementById('suggestions');
const productsGrid = document.getElementById('products');
const searchBtn = document.getElementById('searchBtn');

// Show live suggestions
searchInput.addEventListener('keyup', function() {
    let keyword = this.value.trim();
    if(keyword.length === 0) { suggestionsDiv.style.display='none'; return; }

    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'searchProducts.php?keyword=' + encodeURIComponent(keyword), true);
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            suggestionsDiv.innerHTML = xhr.responseText;
            suggestionsDiv.style.display = 'block';
        }
    };
    xhr.send();
});

// Click suggestion to fill input
suggestionsDiv.addEventListener('click', function(e) {
    if(e.target.classList.contains('suggestion-item')) {
        searchInput.value = e.target.textContent;
        suggestionsDiv.style.display = 'none';
    }
});

// Search button click
searchBtn.addEventListener('click', function() {
    let keyword = searchInput.value.trim();
    if(keyword.length === 0) return;

    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'searchProducts.php?keyword=' + encodeURIComponent(keyword) + '&search=1', true);
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            productsGrid.innerHTML = xhr.responseText;
        }
    };
    xhr.send();
});
</script>

</body>
</html>

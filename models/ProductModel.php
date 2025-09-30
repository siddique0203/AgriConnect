<?php
class ProductModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Generate product id like P001, P002...
    public function generateProductId() {
        $sql = "SELECT product_id FROM Products ORDER BY product_id DESC LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $lastId = intval(substr($row['product_id'], 1));
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }

        return "P" . str_pad($newId, 3, "0", STR_PAD_LEFT);
    }

    // public function createProduct($productId, $farmerId, $name, $description, $price, $stock, $image, $category) {
    //     $sql = "INSERT INTO Products (product_id, farmer_id, name, description, price, stock, image, category)
    //             VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    //     $stmt = mysqli_prepare($this->conn, $sql);
    //     mysqli_stmt_bind_param($stmt, "sssdisss", $productId, $farmerId, $name, $description, $price, $stock, $image, $category);
    //     return mysqli_stmt_execute($stmt);
    // }
    public function createProduct($productId, $farmerId, $name, $description, $price, $stock, $image, $category) {
    $sql = "INSERT INTO Products (product_id, farmer_id, name, description, price, stock, image, category)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssdiss", 
        $productId,    
        $farmerId,     
        $name,         
        $description,  
        $price,        
        $stock,        
        $image,        
        $category      
    );

    return mysqli_stmt_execute($stmt);
}


    public function deleteProduct($productId) {
        $sql = "DELETE FROM Products WHERE product_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $productId);
        return mysqli_stmt_execute($stmt);
    }

    public function searchProductsForFarmer($farmerId, $keyword) {
        $sql = "SELECT * FROM Products WHERE farmer_id=? AND name LIKE ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        $like = "%".$keyword."%";
        mysqli_stmt_bind_param($stmt, "ss", $farmerId, $like);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }

    public function getProductsByFarmer($farmerId) {
        $sql = "SELECT * FROM Products WHERE farmer_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $farmerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }

    // newwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
    public function getAllProducts() {
        $sql = "SELECT p.*, u.name AS farmer_name 
                FROM Products p
                JOIN Users u ON p.farmer_id = u.user_id
                ORDER BY p.created_at DESC";
        $result = mysqli_query($this->conn, $sql);

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }

    //  Get single product details
    public function getProductById($productId) {
        $sql = "SELECT p.*, u.name AS farmer_name 
                FROM Products p 
                JOIN Users u ON p.farmer_id = u.user_id
                WHERE p.product_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result);
    }

    // Admin search across categories and keywords
    public function searchProductsAdmin($category, $keyword) {
        $sql = "SELECT p.*, u.name AS farmer_name
                FROM Products p
                JOIN Users u ON p.farmer_id = u.user_id
                WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($category)) {
            $sql .= " AND p.category = ?";
            $params[] = $category;
            $types .= 's';
        }

        if (!empty($keyword)) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%" . $keyword . "%";
            $types .= 's';
        }

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }
    public function overrideStock($productId, $stock) {
        $sql = "UPDATE Products SET stock = ? WHERE product_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $stock, $productId);
        return mysqli_stmt_execute($stmt);
    }

    public function filterProducts($category = '', $minPrice = '', $maxPrice = '') {
        $sql = "SELECT p.*, u.name AS farmer_name 
                FROM Products p
                JOIN Users u ON p.farmer_id = u.user_id
                WHERE 1=1";

        $params = [];
        $types = '';

        if(!empty($category) && in_array($category, ['vegetable','fruit','grain','dairy','meat','fish','grocery'])) {
            $sql .= " AND p.category = ?";
            $params[] = $category;
            $types .= 's';
        }
        if($minPrice !== '') {
            $sql .= " AND p.price >= ?";
            $params[] = $minPrice;
            $types .= 'd';
        }
        if($maxPrice !== '') {
            $sql .= " AND p.price <= ?";
            $params[] = $maxPrice;
            $types .= 'd';
        }

        $sql .= " ORDER BY p.created_at DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        if(!empty($params)) mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $products = [];
        while($row = mysqli_fetch_assoc($result)) $products[] = $row;
        return $products;
    }

    
}
?>

<?php
// session_start();
// require '../../connection.php'; 

// if (isset($_POST['product_id'])) {
//     $product_id = intval($_POST['product_id']);
//     $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

//     // Fetch product details
//     $sql = "SELECT product_id, product_name, price, store_id 
//             FROM products 
//             WHERE product_id = ?";
//     $stmt = $connection->prepare($sql);
//     $stmt->bind_param("i", $product_id);
//     $stmt->execute();
//     $product = $stmt->get_result()->fetch_assoc();
//     $stmt->close();

//     if ($product) {
//         $store_id = $product['store_id'];

//         // Enforce single-store cart: block items from a different store
//         if (!empty($_SESSION['cart'])) {
//             $existingStoreIds = array_keys($_SESSION['cart']);
//             if (count($existingStoreIds) > 0 && strval($existingStoreIds[0]) !== strval($store_id)) {
//                 $_SESSION['cart_error'] = 'single_store_only';
//                 header("Location: products.php");
//                 exit;
//             }
//         }

//         // Initialize store cart if not set
//         if (!isset($_SESSION['cart'][$store_id])) {
//             $_SESSION['cart'][$store_id] = [];
//         }

//         // Check if product already in cart
//         $found = false;
//         foreach ($_SESSION['cart'][$store_id] as &$item) {
//             if ($item['id'] == $product['product_id']) {
//                 $item['quantity'] += $quantity;
//                 $found = true;
//                 break;
//             }
//         }
//         unset($item);

//         // If new product, add it
//         if (!$found) {
//             $_SESSION['cart'][$store_id][] = [
//                 'id' => $product['product_id'],
//                 'name' => $product['product_name'],
//                 'price' => $product['price'],
//                 'quantity' => $quantity
//             ];
//         }
//     }
// }

// // Redirect back to cart page
// header("Location: products.php");
// exit;
?>
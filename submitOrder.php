<?php
// Include database connection
include './db/db.php';

session_start();

// Check if the form was submitted via POST and quantities exist
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['qty'])) {

    try {
        // Step 1: Insert a new row in the 'orders' table
        $sqlInsertOrder = "INSERT INTO orders (created_by) VALUES (current_timestamp())";
        if ($conn->query($sqlInsertOrder) === TRUE) {
            $orderId = $conn->insert_id; // Get the newly inserted order_id
            $orderSuccess = true;

            // Step 2: Insert each product with qty > 0 into the 'order_line' table
            foreach ($_POST['qty'] as $productId => $qty) {

                if ($qty > 0) {
                    // Extract the price and option ID from the combined value
                    list($price, $optionId) = explode('_', $_POST["price_$productId"]);

                    // Insert order line
                    $sqlInsertOrderLine = $conn->prepare("INSERT INTO order_line (order_id, option_id, qty, price) VALUES (?, ?, ?, ?)");
                    $sqlInsertOrderLine->bind_param("iiid", $orderId, $optionId, $qty, $price);

                    if (!$sqlInsertOrderLine->execute()) {
                        $orderSuccess = false;
                        throw new Exception("Failed to insert order line for product ID $productId: " . $conn->error);
                    }
                }
            }

            // Step 3: Return success or failure message
            if ($orderSuccess) {
                $_SESSION['success_msg'] = "Order placed successfully!";
            } else {
                $_SESSION['error_msg'] = "There was an issue placing your order. Please try again.";
            }

        } else {
            throw new Exception("Failed to create order: " . $conn->error);
        }

    } catch (Exception $e) {
        $_SESSION['error_msg'] = "Error: " . $e->getMessage();
    }

} else {
    $_SESSION['error_msg'] = "No products were selected.";
}

// Redirect back to menu with success or error notification
header("Location: menu.php");
exit();

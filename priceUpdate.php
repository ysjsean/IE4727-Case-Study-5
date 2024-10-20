<?php
include './db/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if any products were selected
    if (!empty($_POST['product'])) {
        $successCount = 0; // To keep track of successful updates
        $errorCount = 0; // To keep track of errors

        foreach ($_POST['product'] as $productId) {

            // Get the options for each product (e.g., Single, Double) and update their prices
            $optionQuery = "SELECT * FROM product_options WHERE product_id = ?";
            $stmt = $conn->prepare($optionQuery);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $optionResult = $stmt->get_result();

            while ($optionRow = $optionResult->fetch_assoc()) {
                $optionId = $optionRow['option_id'];

                // Get the new price for this option
                $newPrice = $_POST["updatePrice_{$productId}_{$optionId}"];

                // Update the price in the database
                $updateQuery = "UPDATE product_options SET price = ? WHERE option_id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("di", $newPrice, $optionId);

                if ($updateStmt->execute()) {
                    $successCount++; // Increment success count
                } else {
                    $errorCount++; // Increment error count
                }

                $updateStmt->close();
            }

            $stmt->close();
        }

        // Prepare success or error messages based on counts
        if ($successCount > 0) {
            $_SESSION['success_msg'] = "$successCount product prices updated successfully.";
        }
        if ($errorCount > 0) {
            $_SESSION['error_msg'] = "$errorCount product prices failed to update.";
        }

    } else {
        $_SESSION['error_msg'] = "No products selected.";
    }

} else {
    $_SESSION['error_msg'] = "Invalid request method.";
}

$conn->close();

// Redirect back to the menu page after the update
header("Location: menu.php");
exit();

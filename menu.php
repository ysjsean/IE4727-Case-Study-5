<?php
session_start();

// Prepare the notification message if available
$notificationMessage = '';
$notificationType = ''; // 'success' or 'error'

if (isset($_SESSION['success_msg'])) {
    $notificationMessage = $_SESSION['success_msg'];
    $notificationType = 'success';
    unset($_SESSION['success_msg']);
}
if (isset($_SESSION['error_msg'])) {
    $notificationMessage = $_SESSION['error_msg'];
    $notificationType = 'error';
    unset($_SESSION['error_msg']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JavaJam Coffee House - Menu</title>
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/menu.css">
    <script defer type="text/javascript" src="./js/global.js"></script>
    <script defer type="text/javascript" src="./js/menu.js"></script>

    <?php
    include './db/db.php';

    // For testing purposes, set role as Admin or User:
    $_SESSION["role"] = "Admin";  // Change to "User" for the non-admin view
    ?>
</head>

<body>
    <!-- Notification container -->
    <div id="notification" class="notification <?php echo $notificationType; ?>">
        <?php echo $notificationMessage; ?>
    </div>

    <div class="wrapper">
        <header>
            <h1>JavaJam Coffee House</h1>
        </header>

        <main>
            <aside class="sideNav">
                <nav>
                    <?php if ($_SESSION["role"] !== "Admin") { ?>
                        <a href="index.html">Home</a> &nbsp;
                        <a class="active" href="menu.php">Menu</a> &nbsp;
                        <a href="music.html">Music</a> &nbsp;
                        <a href="jobs.html">Jobs</a>
                    <?php } else { ?>
                        <a class="active" href="menu.php">Product Price Update</a> &nbsp;
                        <a href="salesReport.php">Daily Sales Report</a>
                    <?php } ?>
                </nav>
            </aside>

            <section>
                <div class="title">
                    <?php if ($_SESSION["role"] !== "Admin") { ?>
                        <p>Coffee at JavaJam</p>
                    <?php } else { ?>
                        <p>Click to update product prices:</p>
                    <?php } ?>
                </div>

                <form action="<?php echo $_SESSION["role"] === "Admin" ? 'priceUpdate.php' : 'submitOrder.php' ?>" method="post">
                    <table>
                        <?php if ($_SESSION["role"] !== "Admin") { ?>
                            <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Description</td>
                                    <td>Quantity</td>
                                    <td>Sub-Total (SGD$)</td>
                                </tr>
                            </thead>
                        <?php } ?>

                        <tbody>
                            <?php
                            // Outer loop: Fetch product details
                            $productQuery = "SELECT * FROM products";
                            $productResult = $conn->query($productQuery);

                            while ($productRow = $productResult->fetch_assoc()) {
                                $productId = $productRow['product_id'];
                                ?>

                                <tr>
                                    <?php if ($_SESSION["role"] === "Admin") { ?>
                                        <td class="product_checkbox">
                                            <input type="checkbox" name="product[]" value="<?php echo $productId; ?>" onclick="togglePriceInput(this, '<?php echo $productId; ?>')">
                                        </td>
                                    <?php } ?>
                                    
                                    <!-- Product details -->
                                    <td class="drinkTitle"><?php echo $productRow['name']; ?></td>
                                    <td class="product-description">
                                        <?php echo $productRow['description']; ?>
                                        <br />

                                        <div class="priceOption">
                                            <?php
                                            $firstOptionChecked = true;
                                            $optionQuery = "SELECT * FROM product_options WHERE product_id = $productId";
                                            $optionResult = $conn->query($optionQuery);

                                            while ($optionRow = $optionResult->fetch_assoc()) {
                                                // If it's an admin, show the price as editable when checked
                                                if ($_SESSION["role"] === "Admin") {
                                                    ?>
                                                    <span id="price_<?php echo $productId; ?>_<?php echo $optionRow['option_id']; ?>">
                                                        <?php echo $optionRow['option_type']; ?>: 
                                                        <span class="priceText priceText_<?php echo $optionRow['product_id'] ?>">$<?php echo $optionRow['price']; ?></span>
                                                        <input type="text" class="updatePrice updatePrice_<?php echo $optionRow['product_id'] ?>" name="updatePrice_<?php echo $productId; ?>_<?php echo $optionRow['option_id']; ?>" value="<?php echo $optionRow['price']; ?>" />
                                                    </span>
                                                    <?php
                                                } else {
                                                    // Non-admin view: display radio buttons for selection
                                                    ?>
                                                    <input type="radio" 
                                                        value="<?php echo $optionRow['price'] . '_' . $optionRow['option_id']; ?>" 
                                                        name="price_<?php echo $productId; ?>" 
                                                        onchange="subTotalUpdate(this)" 
                                                        <?php echo ($firstOptionChecked) ? 'checked' : ''; ?>>
                                                    
                                                    <?php echo $optionRow['option_type'] . ' $' . $optionRow['price']; ?>
                                                    <?php
                                                    $firstOptionChecked = false;
                                                }
                                            }
                                            ?>
                                        </div>
                                        
                                    </td>

                                    <?php if ($_SESSION["role"] !== "Admin") { ?>
                                        <td class="qtyContainer">
                                        <input type="number" class="qty" id="qty_<?php echo $productId; ?>" name="qty[<?php echo $productId; ?>]" min="0" value="0"
                                            onfocus="this.oldvalue = this.value;" onchange="subTotalUpdate(this); this.oldvalue = this.value;">
                                        </td>
                                        <td class="subTotalContainer">
                                            <input type="text" class="subTotal" id="subtotal_<?php echo $productId; ?>" value="0.00" disabled>
                                        </td>
                                    <?php } ?>
                                </tr>

                            <?php } ?>
                            
                            <?php if ($_SESSION["role"] !== "Admin") { ?>
                                <tr>
                                    <td colspan="4">
                                        <div id="totalPriceContainer">
                                            <label for="totalPrice">Total Price SGD$</label>
                                            <input type="text" id="totalPrice" value="0.00" disabled>
                                        </div>
                                        <div class="order-button-container">
                                            <button id="submit_order_button" class="submit-order" disabled>Place My Order</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="update-button-container">
                                            <button type="submit" id="update_price_button" class="update-button" disabled>Update Prices</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </form>
            </section>
        </main>

        <footer>
            <i>Copyright &copy; 2014 JavaJam Coffee House</i>
            <br />
            <a href="mailto:sean@young.com">sean@young.com</a>
        </footer>
    </div>
    <?php
        $conn->close();
    ?>
</body>
</html>

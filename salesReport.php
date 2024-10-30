<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JavaJam Coffee House - Menu</title>
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/salesReport.css">
    <script defer type="text/javascript" src="./js/salesReport.js"></script>

    <?php
        include './db/db.php';

        session_start();

        if (isset($_SESSION["role"]) && $_SESSION["role"] !== "Admin") {
            header('Location: menu.php');
        }
        // Version 1: Subquery
        // $query = "SELECT p.name Product, CASE 
        //     WHEN po.option_type NOT IN ('Single', 'Double') THEN 'Null' 
        //     ELSE po.option_type 
        // END AS Category
        // FROM orders o INNER JOIN order_line ol ON o.order_id = ol.order_id
        // INNER JOIN product_options po ON ol.option_id = po.option_id
        // INNER JOIN products p ON po.product_id = p.product_id
        // WHERE DATE(ol.created_by) = CURDATE() AND p.product_id = (
        //     SELECT p.product_id
        //         FROM orders o INNER JOIN order_line ol ON o.order_id = ol.order_id
        //         INNER JOIN product_options po ON ol.option_id = po.option_id
        //         INNER JOIN products p ON po.product_id = p.product_id
        //         WHERE DATE(ol.created_by) = CURDATE()
        //         GROUP BY p.name
        //         ORDER BY SUM(ol.qty * ol.price) DESC
        //         LIMIT 1 
        // )
        // GROUP BY po.option_type
        // ORDER BY SUM(ol.qty * ol.price) desc
        // LIMIT 1;";

        // $results = $conn->query($query);

        // $row = $results->fetch_assoc();
        // End of version 1

        // Version 2
        $query_for_best_product = "SELECT p.product_id, p.name Product
            FROM orders o INNER JOIN order_line ol ON o.order_id = ol.order_id 
            INNER JOIN product_options po ON ol.option_id = po.option_id 
            INNER JOIN products p ON po.product_id = p.product_id
            WHERE DATE(ol.created_by) = CURDATE()
            GROUP BY p.name
            ORDER BY SUM(ol.qty * ol.price) desc
            LIMIT 1;";

        $results_best_product = $conn->query($query_for_best_product);
        $row_best_product = $results_best_product->fetch_assoc();

        $best_product_id = $row_best_product["product_id"] ?? "";

        if ($best_product_id) {
            $query_for_best_type = "SELECT 
                CASE 
                    WHEN po.option_type NOT IN ('Single', 'Double') THEN 'Null' 
                    ELSE po.option_type 
                END AS Category
                FROM order_line ol
                INNER JOIN product_options po ON ol.option_id = po.option_id
                WHERE DATE(ol.created_by) = CURDATE() and po.product_id = $best_product_id
                GROUP BY Category
                ORDER BY SUM(ol.price * ol.qty) DESC
                LIMIT 1;";

            $results_best_category = $conn->query($query_for_best_type);
            $row_best_category = $results_best_category->fetch_assoc();
        }
        
        // End of version 2
    ?>
</head>
<body>
    <div class="wrapper">
        <header><h1>JavaJam Coffee House</h1></header>
        
        <main>
            <aside class="sideNav">
                <nav>
                    <a href="menu.php">Product Price Update</a> &nbsp;
                    <a class="active" href="salesReport.php">Daily Sales Report</a>
                </nav>
            </aside>
            
            <section>
                <div class="title">
                    <p>Click to generate daily sales report:</p>
                </div>

                <div class="report-container">
                    <!-- Version 1 -->
                    <!-- <input type="checkbox" id="productReport" class="checkbox" onchange="loadContentBasedOnCheckbox(this, 'salesByProductContainer', 'salesByProduct.php')"> -->
                    
                    <!-- Version 2 -->
                    <input type="checkbox" id="productReport" class="checkbox" onchange="return checkBoxRedirect()">
                    <label for="productReport">Total dollar and quantity sales by products</label>
                    <div id="salesByProductContainer" class="hidden"></div>

                    <!-- Version 1 -->
                    <!-- <input type="checkbox" id="categoryReport" class="checkbox" onchange="loadContentBasedOnCheckbox(this, 'salesByCategoryContainer', 'salesByCategories.php')"> -->
                    
                    <!-- Version 2 -->
                    <input type="checkbox" id="categoryReport" class="checkbox" onchange="return checkBoxRedirect()">
                    <label for="categoryReport">Total dollar and quantity sales by categories</label>
                    <div id="salesByCategoryContainer" class="hidden"></div>

                    <div id="bestProduct">
                        <p id="bestProductDesc">Popular option of best selling product:</p>
                        <span id="bestProductAns">
                            <?php
                                // Version 1
                                // $category = $row['Category'] ?? "";
                                // $product = $row['Product'] ?? "";

                                // Version 2
                                $product = $row_best_product["Product"] ?? "";
                                $category = $row_best_category["Category"] ?? "";

                                if ($category && $product) {
                                    echo "$category of $product";
                                } else {
                                    echo "No daily sales";
                                }
                                
                            ?>
                        </span>
                    </div>
                </div>
            </section>
            
        </main>
        
    
        <footer>
            <i>Copyright &copy; 2014 JavaJam Coffee House</i>
            <br/>
            <a href="mailto:sean@young.com">sean@young.com</a>
        </footer>
    </div>
    
</body>
</html>
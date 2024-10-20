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

        if (isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
            header('menu.php');
        }

        $query = "SELECT p.name Product, CASE 
            WHEN po.option_type NOT IN ('Single', 'Double') THEN 'Null' 
            ELSE po.option_type 
        END AS Category
        FROM orders o INNER JOIN order_line ol ON o.order_id = ol.order_id
        INNER JOIN product_options po ON ol.option_id = po.option_id
        INNER JOIN products p ON po.product_id = p.product_id
        WHERE DATE(ol.created_by) = CURDATE() AND p.product_id = (
            SELECT p.product_id
                FROM orders o INNER JOIN order_line ol ON o.order_id = ol.order_id
                INNER JOIN product_options po ON ol.option_id = po.option_id
                INNER JOIN products p ON po.product_id = p.product_id
                WHERE DATE(ol.created_by) = CURDATE()
                GROUP BY p.name
                ORDER BY SUM(ol.qty * ol.price) DESC
                LIMIT 1 
        )
        GROUP BY po.option_type
        ORDER BY ol.qty desc
        LIMIT 1;";

        $results = $conn->query($query);

        $row = $results->fetch_assoc();
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
                                $category = $row['Category'];
                                $product = $row['Product'];
                                echo "$category of $product";
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
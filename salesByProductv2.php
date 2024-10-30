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

        if (session_status() == PHP_SESSION_NONE) {
            // Session has not started, so start it
            session_start();
        }

        if (isset($_SESSION["role"]) && $_SESSION["role"] !== "Admin") {
            header('Location: menu.php');
        }

        $query = "SELECT p.name Product, SUM(ol.qty * ol.price) 'Total Dollar Sales', SUM(ol.qty) 'Quantity Sales' 
        FROM orders o INNER JOIN order_line ol ON o.order_id = ol.order_id 
        INNER JOIN product_options po ON ol.option_id = po.option_id 
        INNER JOIN products p ON po.product_id = p.product_id
        WHERE DATE(ol.created_by) = CURDATE()
        GROUP BY p.name
        ORDER BY 'Total Dollar Sales';";

        $results = $conn->query($query);

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
                    <p>Total dollar and quantity sales by products:</p>
                </div>

                <table class='dailyReportTable'>
                    <thead>
                        <tr class='dailyReportHeader'>
                            <th>Product</th>
                            <th>Total Dollar Sales</th>
                            <th>Quantity Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while ($row = $results->fetch_assoc()) {
                                $productName = $row['Product'];
                                $totalDollarSales = $row['Total Dollar Sales'];
                                $qtySales = $row['Quantity Sales'];
                                
                                echo
                                "
                                    <tr class='dailyReportData'>
                                        <td>$productName</td>
                                        <td>$$totalDollarSales</td>
                                        <td>$qtySales</td>
                                    </tr>
                                ";
                            }
                        ?>
                    </tbody>
                </table>
                <div class="back-button-container">
                    <button type="button" class="back-button" onclick="javascript:history.back()">Back</button>
                </div>
            </section>
        </main>
        
    
        <footer>
            <i>Copyright &copy; 2014 JavaJam Coffee House</i>
            <br/>
            <a href="mailto:sean@young.com">sean@young.com</a>
        </footer>
    </div>
    <?php
        $conn->close();
    ?>
</body>
</html>
<?php
include './db/db.php';

if (session_status() == PHP_SESSION_NONE) {
    // Session has not started, so start it
    session_start();
}

if (isset($_SESSION["role"]) && $_SESSION["role"] === "Admin") {
    $query = "SELECT p.name Product, SUM(ol.qty * ol.price) 'Total Dollar Sales', SUM(ol.qty) 'Quantity Sales' 
    FROM orders o INNER JOIN order_line ol ON o.order_id = ol.order_id 
    INNER JOIN product_options po ON ol.option_id = po.option_id 
    INNER JOIN products p ON po.product_id = p.product_id
    WHERE DATE(ol.created_by) = CURDATE()
    GROUP BY p.name
    ORDER BY 'Total Dollar Sales';";

    $results = $conn->query($query);

    $response = "";
    $response .= 
    "<table class='dailyReportTable'><thead>
        <tr class='dailyReportHeader'>
            <th>Product</th>
            <th>Total Dollar Sales</th>
            <th>Quantity Sales</th>
        </tr>
    </thead>
    <tbody>";
    
    $bestSellingProduct = '';
    $previousSales = 0;
    while ($row = $results->fetch_assoc()) {
        $productName = $row['Product'];
        $totalDollarSales = $row['Total Dollar Sales'];
        $qtySales = $row['Quantity Sales'];

        $bestSellingProduct = $row['Total Dollar Sales'] > $previousSales ? $row['Product'] : $bestSellingProduct;
        $previousSales = $row['Total Dollar Sales'];
        
        $response .= 
        "
            <tr class='dailyReportData'>
                <td>$productName</td>
                <td>$$totalDollarSales</td>
                <td>$qtySales</td>
            </tr>
        ";
    }
    $response .= "</tbody></table>";
    // setcookie("bestSellingProduct", $bestSellingProduct, time() + 86400, "/");
    
    $conn->close();
    echo json_encode(["status" => "Success", "response" => $response, "bestSellingProduct" => $bestSellingProduct]);
}
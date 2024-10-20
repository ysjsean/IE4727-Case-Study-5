<?php
include './db/db.php';

if (session_status() == PHP_SESSION_NONE) {
    // Session has not started, so start it
    session_start();
}

if (isset($_SESSION["role"]) && $_SESSION["role"] === "Admin") {
    $query = "SELECT 
    CASE 
        WHEN po.option_type NOT IN ('Single', 'Double') THEN 'Null' 
        ELSE po.option_type 
    END AS Category, 
    SUM(ol.price * ol.qty) AS 'Total Dollar Sales', 
    SUM(ol.qty) AS 'Quantity Sales'
    FROM order_line ol
    INNER JOIN product_options po ON ol.option_id = po.option_id
    WHERE DATE(ol.created_by) = CURDATE()
    GROUP BY Category
    ORDER BY 
    CASE 
        WHEN po.option_type NOT IN ('Single', 'Double') THEN 1
        WHEN po.option_type = 'Single' THEN 2
        WHEN po.option_type = 'Double' THEN 3
    END;";

    $results = $conn->query($query);
    $response = "";

    $response .= 
    "<table class='dailyReportTable'><thead>
        <tr class='dailyReportHeader'>
            <th>Category</th>
            <th>Total Dollar Sales</th>
            <th>Quantity Sales</th>
        </tr>
    </thead>
    <tbody>";

    $bestSellingCategory = '';
    $previousSales = 0;

    while ($row = $results->fetch_assoc()) {
        $category = $row['Category'];
        $totalDollarSales = $row['Total Dollar Sales'];
        $qtySales = $row['Quantity Sales'];

        $bestSellingCategory = (float)$row['Total Dollar Sales'] > $previousSales ? $row['Category'] : $bestSellingCategory;
        $previousSales = (float) $row['Total Dollar Sales'];

        $response .= 
        "
            <tr class='dailyReportData'>
                <td>$category</td>
                <td>$$totalDollarSales</td>
                <td>$qtySales</td>
            </tr>
        ";
    }
    $response .= "</tbody></table>";
    // setcookie("bestSellingCategory", $bestSellingCategory, time() + 86400, "/");
    $conn->close();
    echo json_encode(["status" => "Success", "response" => $response, "bestSellingCategory" => $bestSellingCategory]);
}
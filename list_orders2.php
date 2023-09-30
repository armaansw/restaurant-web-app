<?php
$date = $_POST['date'];

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO('mysql:host=localhost;dbname=restaurantdb',
    "root", "", $options);

    $sql = "SELECT
                orderE.orderID,
                customer.firstName,
                customer.lastName,
                foodItem.foodName,
                orderE.totalPrice,
                orderE.tip,
                delivery.firstName AS deliveryFirstName,
                delivery.lastName AS deliveryLastName
            FROM
                orderE
            JOIN customer ON orderE.customerEmailAddress = customer.emailAddress
            JOIN foodItem ON orderE.orderID = foodItem.orderOrderID
            JOIN delivery ON orderE.deliveryEmployeeID = delivery.employeeID
            WHERE
                orderE.placementDate = :date";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':date', $date);

    $stmt->execute();
    $orders = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link rel="stylesheet" href="list_orders.css">
</head>
<body>
    <h1>Order List for <?php echo $date; ?></h1>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Item</th>
                <th>Total Price</th>
                <th>Tip</th>
                <th>Delivered By</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['orderID']; ?></td>
                    <td><?php echo $order['firstName'] . ' ' . $order['lastName']; ?></td>
                    <td><?php echo $order['foodName']; ?></td>
                    <td><?php echo $order['totalPrice']; ?></td>
		            <td><?php echo $order['tip']; ?></td>
                    <td><?php echo $order['deliveryFirstName'] . ' ' . $order['deliveryLastName']; ?></td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

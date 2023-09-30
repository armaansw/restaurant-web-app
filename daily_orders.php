<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="daily_orders.css">
</head>
<body>
    <header>
        <h1>Order Summary</h1>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Number of Orders</th>
                </tr>
            </thead>
            <tbody>
                <?php
        
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];
                try {
                    $pdo = new PDO('mysql:host=localhost;dbname=restaurantdb',
                    "root", "", $options);
                } catch (PDOException $e) {
                    throw new PDOException($e->getMessage(), (int)$e->getCode());
                }
                
                $sql = "SELECT placementDate, COUNT(orderID) as orderCount FROM orderE GROUP BY placementDate ORDER BY placementDate";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                while ($row = $stmt->fetch()) {
                    echo "<tr><td>" . $row["placementDate"] . "</td><td>" . $row["orderCount"] . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>

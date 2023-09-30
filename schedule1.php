<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Schedules</title>
    <link rel="stylesheet" href="schedule.css">
</head>
<body>
    <header>
        <h1>Employee Schedules</h1>
    </header>
    <main>
        <form action="schedule2.php" method="post">
            <label for="employee">Select an Employee:</label>
            <select name="employeeID" id="employee">
                <?php
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ];
                try {
                    $pdo = new PDO("mysql:host=localhost;dbname=restaurantdb", "root", "", $options);

                    $stmt = $pdo->prepare("SELECT employeeID, firstName, lastName FROM
                    (SELECT employeeID, firstName, lastName FROM chef
                    UNION ALL
                    SELECT employeeID, firstName, lastName FROM manager
                    UNION ALL
                    SELECT employeeID, firstName, lastName FROM serverE
                    UNION ALL
                    SELECT employeeID, firstName, lastName FROM delivery) AS employees
                    ORDER BY lastName, firstName");
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['employeeID']}'>{$row['firstName']} {$row['lastName']}</option>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                $pdo = null;
                ?>
            </select>
            <input type="submit" value="Show Schedule">
        </form>
    </main>
</body>
</html>
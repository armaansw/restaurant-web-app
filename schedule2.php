<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Schedule</title>
    <link rel="stylesheet" href="schedule.css">
</head>
<body>
    <header>
        <h1>Employee Schedule</h1>
    </header>
    <main>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employeeID'])) {
            $employeeID = $_POST['employeeID'];
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=restaurantdb", "root", "", $options);
    
                $stmt = $pdo->prepare("SELECT firstName, lastName FROM
                (SELECT employeeID, firstName, lastName FROM chef
                UNION ALL
                SELECT employeeID, firstName, lastName FROM manager
                UNION ALL
                SELECT employeeID, firstName, lastName FROM serverE
                UNION ALL
                SELECT employeeID, firstName, lastName FROM delivery) AS employees
                WHERE employeeID = :employeeID");
                $stmt->bindParam(':employeeID', $employeeID);
                $stmt->execute();
                $employee = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<h2>Schedule for {$employee['firstName']} {$employee['lastName']}</h2>";
                $stmt = $pdo->prepare("SELECT date, startTime, endTime FROM
                (SELECT date, startTime, endTime, chefEmployeeID AS employeeID FROM chefSchedule
                UNION ALL
                SELECT date, startTime, endTime, managerEmployeeID AS employeeID FROM managerSchedule
                UNION ALL
                SELECT date, startTime, endTime, serverEmployeeID AS employeeID FROM serverSchedule
                UNION ALL
                SELECT date, startTime, endTime, deliveryEmployeeID AS employeeID FROM deliverySchedule) AS schedules
                WHERE employeeID = :employeeID AND DAYOFWEEK(date) BETWEEN 2 AND 6
                ORDER BY date");
                $stmt->bindParam(':employeeID', $employeeID);
                $stmt->execute();

                echo "<table>
                        <tr>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>";

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['date']}</td>
                            <td>{$row['startTime']}</td>
                            <td>{$row['endTime']}</td>
                          </tr>";
                }
                echo "</table>";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $pdo = null;
        } else {
            echo "<p>No employee selected. Please go back and choose an employee.</p>";
        }
        ?>
        <p><a href="schedule1.php">Go back</a></p>
    </main>
</body>
</html>

               

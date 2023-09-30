<?php
function connectDatabase() {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=restaurantdb',
        "root", "", $options);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

function customerExists($emailAddress) {
    $pdo = connectDatabase();
    $sql = "SELECT * FROM customer WHERE emailAddress = :emailAddress";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':emailAddress', $emailAddress, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->rowCount() > 0;
}

function addCustomer($customerData) {
    $pdo = connectDatabase();
    $sql = "INSERT INTO customer (emailAddress, firstName, lastName, phoneNumber, street, city, zip) VALUES (:emailAddress, :firstName, :lastName, :phoneNumber, :street, :city, :zip)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($customerData);

    $accountData = [
        ':credit' => 5.00,
        ':customerEmailAddress' => $customerData[':emailAddress']
    ];
    $sql = "INSERT INTO account (credit, customerEmailAddress) VALUES (:credit, :customerEmailAddress)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($accountData);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailAddress = $_POST['emailAddress'];
    if (customerExists($emailAddress)) {
        echo 'Customer already exists';
    } else {
        $customerData = [
            ':emailAddress' => $emailAddress,
            ':firstName' => $_POST['firstName'],
            ':lastName' => $_POST['lastName'],
            ':phoneNumber' => $_POST['phoneNumber'],
            ':street' => $_POST['street'],
            ':city' => $_POST['city'],
            ':zip' => $_POST['zip']
        ];
        addCustomer($customerData);
        echo 'Customer added successfully';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer</title>
    <link rel="stylesheet" href="add_customer.css">
</head>
<body>
    <h1>Add a new customer</h1>
    <form method="post">
        <label for="firstName">First Name:</label>
        <input type="text" name="firstName" required>
        <br>
        <label for="lastName">Last Name:</label>
        <input type="text" name="lastName" required>
        <br>
        <label for="emailAddress">Email Address:</label>
        <input type="email" name="emailAddress" required>
        <br>
        <label for="phoneNumber">Phone Number:</label>
        <input type="text" name="phoneNumber" required>
        <br>
        <label for="street">Street:</label>
        <input type="text" name="street" required>
        <br>
        <label for="city">City:</label>
        <input type="text" name="city" required>
        <br>
        <label for="zip">Zip Code:</label>
        <input type="text" name="zip" required>
        <br>
        <input type="submit" value="Add Customer">
    </form>

    <h2>List of Customers</h2>
    <table>
        <thead>
            <tr>
                <th>Email Address</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $pdo = connectDatabase();
            $sql = "SELECT emailAddress, firstName, lastName FROM customer";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['emailAddress']) . '</td>';
                echo '<td>' . htmlspecialchars($row['firstName']) . '</td>';
                echo '<td>' . htmlspecialchars($row['lastName']) . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>

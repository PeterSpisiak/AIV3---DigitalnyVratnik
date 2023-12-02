<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoznam zamestnancov</title>
</head>
<body>
<div class="container">
    <div class="row">
        <h1 class="text-center mt-2 mb-5">
            Zoznam zamestnancov
        </h1>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
            <thead>
                <tr>
                <th scope="col">Meno</th>
                <th scope="col">Pracovna doba</th>
                <th scope="col">Id Karty</th>
                <th scope="col"></th>
                </tr>
            </thead>
<?php
$servername = "localhost";
$username = "Dochadzka";
$password = "klopklop296";
$dbname = "dochadzka";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Name query
$sql = "SELECT * FROM `zamestnanci`" ;

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tbody>";
        echo "<tr>";
        echo "<td>".$row["meno"]."</td>";
        echo "<td>".$row["doba"]."</td>";
        echo "<td>".$row["karta_id"]."</td>";
        echo "<td><a href=\"userattendance.php?id=".$row["id"]."\" class=\"text-center\"><button type=\"button\" class=\"btn btn-primary\">Dochadzka</button></a></td>";
        echo "</tr>";
        echo "</tbody>";
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>
            </table>
        </div>
    </div>
</div>
</body>
</html>

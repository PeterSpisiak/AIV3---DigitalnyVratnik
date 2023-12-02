<?php
    $id = $_GET["id"];
    $name = "";
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
    $sql1 = "SELECT * FROM `prichody` WHERE zamestnanec_id=" . $id;
    $result1 = $conn->query($sql1);

    // Name query
    $sql2 = "SELECT * FROM `odchody` WHERE zamestnanec_id=" . $id;
    $result2 = $conn->query($sql2);

    $rows1 = 0;
    $rows2 = 0;

    if ($result1->num_rows > 0) {
        // Fetch results into an array
        $rows1 = $result1->fetch_all(MYSQLI_ASSOC);
    
    }

    if ($result2->num_rows > 0) {
        // Fetch results into an array
        $rows2 = $result2->fetch_all(MYSQLI_ASSOC);
    }

    $sql = "SELECT * FROM `zamestnanci` WHERE id=".id ;

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $name = $row["meno"];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dochadzka zamestnanca</title>
</head>
<body>
<div class="container">
    <div class="row">
        <h1 class="text-center mt-2 mb-5">
            Dochadzka zamestnanca <?php echo $name;?>
        </h1>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
            <thead>
                <tr>
                <th scope="col">Den</th>
                <th scope="col">Prichod</th>
                <th scope="col">Odchod</th>
                </tr>
            </thead>
<?php

                if ($result1->num_rows > 0) {
                    $a = 1;
                    foreach($rows1 as $rowin) {
                        $arrival = new DateTime($rowin["cas"]);
                        if ($result2->num_rows > 0) {
                            foreach($rows2 as $rowout) {
                                $departure = new DateTime($rowout["cas"]);

                                if($arrival->format('Y-m-d') === $departure->format('Y-m-d'))
                                {
                                    echo "<tbody>";
                                    echo "<tr>";
                                    echo "<td>".$arrival->format('Y-m-d')."</td>";
                                    echo "<td>".$arrival->format('H:i')."</td>";
                                    echo "<td>".$departure->format('H:i')."</td>";
                                    echo "</tr>";
                                    echo "</tbody>";
                                    continue 2;
                                }
                            }
                            echo "<tbody>";
                            echo "<tr>";
                            echo "<td>".$arrival->format('Y-m-d')."</td>";
                            echo "<td>".$arrival->format('H:i')."</td>";
                            echo "<td>Zamestnanec sa neodhlasil</td>";
                            echo "</tr>";
                            echo "</tbody>";
                        }
                    }
                }else {
                    echo "No data";
                }

                // Close connection
                $conn->close();
            ?>
            </table>
        </div>
    </div>
    <div class="row">
    <a href="index.php" class="text-center"><button type="button" class="btn btn-primary">Zoznam</button></a>
    </div>
</div>
</body>
</html>

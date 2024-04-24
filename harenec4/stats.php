<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once "../config4.php";

$apiBaseUrl = 'https://node10.webte.fei.stuba.sk/harenec4/server/api';

$errmsg = "";
$successmsg = "";

$query = "SELECT * FROM unique_users";

$stmt = $pdo->prepare($query);
$stmt->execute();

$connectionTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOVOLENá</title>
    <link rel="icon" type="image/x-icon" href="../dawg.png">
    <link rel="shortcut icon" href="#">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <div class="container">
        <nav class="main-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php">Výletovač</a>
                </li>
                <li class="nav-item">
                    <a href="stats.php">Štatistiky</a>
                </li>
            </ul>
        </nav>
    </div>
    <main class="container">
        <h1>Štatistiky návštevnosti.</h1>

        <h2>Návštevnosť stránky:</h2>
        <div class="in-row spaced-between ">
            <div class="in-column centered spacer-r">
                <p>00:00 - 06:00 </p>
                <p>
                    <?php echo $connectionTimes[0]["amount"] ?>
                </p>
            </div>
            <div class="in-column centered spacer-r">
                <p>06:00 - 15:00 </p>
                <p>
                    <?php echo $connectionTimes[1]["amount"] ?>
                </p>
            </div>
            <div class="in-column centered spacer-r">
                <p>15:00 - 21:00 </p>
                <p>
                    <?php echo $connectionTimes[2]["amount"] ?>
                </p>
            </div>
            <div class="in-column centered spacer-r">
                <p>21:00 - 24:00 </p>
                <p>
                    <?php echo $connectionTimes[3]["amount"] ?>
                </p>
            </div>


        </div>

        <h2>Najvyhľadávanejšie destinácie:</h2>
        <div class="table-nav">
            <div class="table-selector">
                <h4>Počet záznamov na stránku:</h4>
                <select name="page-length" id="page-length">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="-1">Všetky</option>
                </select>
            </div>

        </div>

        <table id="myTable" class="table table-striped table-hover" width="100%">
            <thead>
                <tr>
                    <th>Miesto</th>
                    <th>Štát</th>
                    <th>Počet vyhľadávaní</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
    <script src="js/tableData.js"></script>
</body>

</html>
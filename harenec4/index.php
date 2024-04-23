<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$apiBaseUrl = 'https://node10.webte.fei.stuba.sk/harenec2/api';

$errmsg = "";
$successmsg = "";


?>

<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOVOLENá</title>
    <link rel="icon" type="image/x-icon" href="../dawg.png">
    <link rel="shortcut icon" href="#">
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
        <h1>Ideme na výlet.</h1>

        <div class="content-outline in-row centered">
            <div class="form-input">
                <p>Destinácia/Miesto</p>
                <input type="text" name="arrival-place" id="arrival-place" placeholder="Napr. Skopje">
                <p id="err-arrival-place" class="err hidden"></p>
            </div>
            <div class="form-input">
                <p>Dátum príchodu</p>
                <input type="date" name="arrival-date" id="arrival-date" min="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d', strtotime('+1 year')) ?>" required>
                <p id="err-arrival-date" class="err hidden"></p>
            </div>

            <button type="submit" id="btn-info">Nájdi info</button>


        </div>

        <div id="place-content" class="content-outline hidden">
            <div class="in-row centered-v">
                <h2 id="place" class="spacer-r">Prešov /</h2>
                <img id="flag" src="https://flagcdn.com/sk.svg" alt="vlajka">

            </div>
            <p id="cur-temp">Aktuálna teplota / 15°C / Slnečno</p>
            <p id="avg-temp">Priemerná teplota pre obdobie (2023) / 12°C</p>
            <p id="country">Štát / Slovakia</p>
            <p id="capital">Hlavné mesto / Bratislava</p>
            <p id="currency">Mena / Euro / 1 € = 30 SKK</p>
        </div>

    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="js/trip.js"></script>
</body>

</html>
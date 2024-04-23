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
    <title>AIS Rozvrh hodín</title>
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
        <h1>Ideme na výlet.</h1>
        
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
        <div id="table-edit-nav" class="hidden">
            <div class="table-nav table-edit">
                <button id="record-add" class="btn-compact">Pridaj záznam</button>
                <button id="record-edit" class="btn-compact">Uprav záznam</button>
                <button id="record-remove" class="btn-compact">Vymaž záznam</button>
            </div>

            <div class="table-nav table-edit form-outline hidden">
                <form id="form-record-add" class="form-record" method="post"
                    action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="formId" value="form-record-add">

                    <div class="form-input">
                        <label for="add-name">
                            Deň:
                        </label>
                        <select name="add-day" id="add-day">
                            <option value="">Vyberte deň</option>
                            <option value="Po">Pondelok</option>
                            <option value="Ut">Utorok</option>
                            <option value="St">Streda</option>
                            <option value="Št">Štvrtok</option>
                            <option value="Pi">Piatok</option>
                        </select>
                    </div>

                    <div class="form-input">
                        <label for="add-time_from">
                            Od:
                        </label>
                        <input type="text" name="add-time_from" value="" id="add-time_from" placeholder="napr. 9:00">
                        <span class="err" id="err-add-time_from"></span>
                    </div>

                    <div class="form-input">
                        <label for="add-time_to">
                            Do:
                        </label>
                        <input type="text" name="add-time_to" value="" id="add-time_to" placeholder="napr. 10:50">
                        <span class="err" id="add-time_to"></span>
                    </div>

                    <div class="form-input">
                        <label for="add-subject">
                            Názov predmetu:
                        </label>
                        <input type="text" name="add-subject" value="" id="add-subject"
                            placeholder="napr. Pretekárske inžinierstvo">
                        <span class="err" id="err-add-subject"></span>
                    </div>

                    <div class="form-input">
                        <label for="add-action">
                            Typ:
                        </label>
                        <input type="text" name="add-action" value="" id="add-action" placeholder="napr. Prednáška">
                        <span class="err" id="err-add-action"></span>
                    </div>

                    <div class="form-input">
                        <label for="add-room">
                            Miestnosť:
                        </label>
                        <input type="text" name="add-room" value="" id="add-room" placeholder="napr. c710">
                        <span class="err" id="err-add-room"></span>
                    </div>

                    <div class="form-input">
                        <label for="add-teacher">
                            Učiteľ:
                        </label>
                        <input type="text" name="add-teacher" value="" id="add-teacher" placeholder="napr. J. Bajusz">
                        <span class="err" id="err-add-teacher"></span>
                    </div>

                    <div class="form-input">
                        <button id="add-submit-btn" type="submit">Uložiť záznam</button>
                    </div>
                </form>
            </div>


            <div class="table-nav table-edit form-outline hidden">
                <p>Nájdi záznam:</p>
                <div class="form-input">
                    <select name="choose-action-id" id="choose-action-id">
                        <option value="0">Vyberte záznam</option>
                        <?php
                        // $response = sendRequest($apiBaseUrl . "/api_timetable.php/timetable");
                        // $data = json_decode($response, true);
                        // $data2 = $data['response'];
                        // $timetable = json_decode($data2, true);

                        // foreach ($timetable as $row) {
                        //     echo "<option value=" . $row['id'] . ">" . $row['day'] . ", " . $row['time_from'] . ", " . $row['subject'] . ", " . $row['action'] . "</option>";
                        // }
                        ?>
                    </select>
                </div>
                <form id="form-record-edit" class="form-record hidden" method="post"
                    action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="formId" value="form-record-edit">
                    <input type="hidden" name="tableActionId" id="tableActionId" value="">
                    <div class="form-input">
                        <label for="edit-name">
                            Deň:
                        </label>
                        <select name="edit-day" id="edit-day">
                            <option value="">Vyberte deň</option>
                            <option value="Po">Pondelok</option>
                            <option value="Ut">Utorok</option>
                            <option value="St">Streda</option>
                            <option value="Št">Štvrtok</option>
                            <option value="Pi">Piatok</option>
                        </select>
                    </div>

                    <div class="form-input">
                        <label for="edit-time_from">
                            Od:
                        </label>
                        <input type="text" name="edit-time_from" value="" id="edit-time_from" placeholder="napr. 9:00">
                        <span class="err" id="err-edit-time_from"></span>
                    </div>

                    <div class="form-input">
                        <label for="edit-time_to">
                            Do:
                        </label>
                        <input type="text" name="edit-time_to" value="" id="edit-time_to" placeholder="napr. 10:50">
                        <span class="err" id="edit-time_to"></span>
                    </div>

                    <div class="form-input">
                        <label for="edit-subject">
                            Názov predmetu:
                        </label>
                        <input type="text" name="edit-subject" value="" id="edit-subject"
                            placeholder="napr. Pretekárske inžinierstvo">
                        <span class="err" id="err-edit-subject"></span>
                    </div>

                    <div class="form-input">
                        <label for="edit-action">
                            Typ:
                        </label>
                        <input type="text" name="edit-action" value="" id="edit-action" placeholder="napr. Prednáška">
                        <span class="err" id="err-edit-action"></span>
                    </div>

                    <div class="form-input">
                        <label for="edit-room">
                            Miestnosť:
                        </label>
                        <input type="text" name="edit-room" value="" id="edit-room" placeholder="napr. c710">
                        <span class="err" id="err-edit-room"></span>
                    </div>

                    <div class="form-input">
                        <label for="edit-teacher">
                            Učiteľ:
                        </label>
                        <input type="text" name="edit-teacher" value="" id="edit-teacher" placeholder="napr. J. Bajusz">
                        <span class="err" id="err-edit-teacher"></span>
                    </div>

                    <div class="form-input">
                        <button id="edit-submit-btn" type="submit">Uložiť zmenu</button>
                    </div>

                </form>
            </div>
        </div>

        <table id="myTable" class="table table-striped table-hover" width="100%">
            <thead>
                <tr>
                    <th>Deň</th>
                    <th>Od</th>
                    <th>Do</th>
                    <th>Predmet</th>
                    <th>Akcia</th>
                    <th>Miestnosť</th>
                    <th>Vyučujúci</th>
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
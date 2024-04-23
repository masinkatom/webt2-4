
$(document).ready(function () {
    document.getElementById("page-length").addEventListener("input", handleTableRows);

    var table;
    var timetable;
    
    setTable();

    async function setTable() {
        //await refreshData();
        table = $('#myTable').DataTable({
            responsive: true,
            data: timetable,
            columns: [
                { data: 'day' },
                { data: 'time_from' },
                { data: 'time_to' },
                { data: 'subject' },
                { data: 'action' },
                { data: 'room' },
                { data: 'teacher' }
            ],
            scrollX: true,
            layout: {
                topStart: null,
                topEnd: null,
                bottomStart: null,
                bottomEnd: 'paging'
            }
        });
    }


    async function refreshData() {
        timetable = await requestApi('/api_timetable.php/timetable');
        console.log("TIMETABLE: " + timetable);
    }

    function submitTimetable() {
        $.ajax({
            type: "POST",
            url: "setAisTimetable.php",
            data: {
                action: "submit"
            }, // Send data to the server
            success: function (response) {
                alert(response);
                // You can perform additional actions after successful submission
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert("Error occurred while submitting data.");
            }
        });
    }

    

    function deleteTimetable() {
        $.ajax({
            type: "POST",
            url: "setAisTimetable.php",
            data: {
                action: "delete"
            }, // Send data to the server
            success: function (response) {
                alert(response);
                // You can perform additional actions after successful submission
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert("Error occurred while deleting data.");
            }
        });
    }

    async function refreshTimetable() {
        await refreshData();
        $('#myTable').DataTable().clear().draw();
        // Populate DataTable with updated JSON data
        $('#myTable').DataTable().rows.add(timetable).draw();

    }


    async function showEditForm(e) {
        let timetableAction = await requestApi("/api_timetable.php/timetableAction/" + e.target.value);
        document.getElementById("form-record-edit").classList.remove("hidden");
        if (e.target.value === "0") {
            document.getElementById("form-record-edit").classList.add("hidden");
        }

        preFillInputs(timetableAction[0]);
    }

    async function showEditForm2(e) {
        document.getElementById("form-record-remove").classList.remove("hidden");
        if (e.target.value === "0") {
            document.getElementById("form-record-remove").classList.add("hidden");
        }
        document.getElementById("tableActionId2").value = e.target.value;
    }

    function handleTableRows(e) {
        table.page.len(e.target.value).draw();
    }

    function filterYearRows(e) {
        table.column(0).search(e.target.value).draw();
    }

    
    async function requestApi(endpoint) {
        try {
            const apiUrl = 'https://node10.webte.fei.stuba.sk/harenec2/api' + endpoint;
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
            return []; // Return empty array on error
        }
    }
    
});

let opened1 = false;
function showDiv(elemName) {
    let div = document.getElementById(elemName);
    if (!opened1) {
        div.classList.remove("hidden");
        opened1 = true;
    }
    else {
        div.classList.add("hidden");
        opened1 = false;
    }
    
}

function preFillInputs(timetableAction) {
    document.getElementById("tableActionId").value = timetableAction.id;
    document.getElementById("edit-day").value = timetableAction.day;
    document.getElementById("edit-time_from").value = timetableAction.time_from;
    document.getElementById("edit-time_to").value = timetableAction.time_to;
    document.getElementById("edit-subject").value = timetableAction.subject;
    document.getElementById("edit-action").value = timetableAction.action;
    document.getElementById("edit-room").value = timetableAction.room;
    document.getElementById("edit-teacher").value = timetableAction.teacher;

}

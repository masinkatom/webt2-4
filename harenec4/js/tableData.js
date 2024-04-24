
$(document).ready(function () {
    document.getElementById("page-length").addEventListener("input", handleTableRows);

    var table;
    var places;
    
    setTable();

    async function setTable() {
        await refreshData();
        table = $('#myTable').DataTable({
            responsive: true,
            data: places,
            columns: [
                { data: 'place' },
                { data: 'country' },
                { data: 'searched_amount' }
            ],
            scrollX: true,
            layout: {
                topStart: null,
                topEnd: null,
                bottomStart: null,
                bottomEnd: 'paging'
            },
            order: [[2, "desc"]]
        });
    }


    async function refreshData() {
        places = await requestApi('/api_stats.php/places');
        console.log(places);
    }


    function handleTableRows(e) {
        table.page.len(e.target.value).draw();
    }

    function filterYearRows(e) {
        table.column(0).search(e.target.value).draw();
    }


    
    async function requestApi(endpoint) {
        try {
            const apiUrl = 'https://node10.webte.fei.stuba.sk/harenec4/server/api' + endpoint;
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

    async function getIpAddress() {
        try {
            const response = await fetch('https://api.ipify.org?format=json');
            const data = await response.json();
            return data.ip;
        } catch (error) {
            console.error('Error:', error);
            return null;
        }
    }

    async function storeVisitorInfo() {
        let currTime = new Date();
        let visitor = localStorage.getItem("visitor");
        if (visitor !== null) {
            visitor = JSON.parse(visitor);
            console.log(currTime.getTime() - visitor.timestamp);
            if (currTime.getTime() - visitor.timestamp < 3600000) {
                return;
            } 
        }

        const ipAddress = await getIpAddress();
        if (ipAddress) {
            const timestamp = currTime.getTime();
            const visitorData = {
                ip: ipAddress,
                timestamp: timestamp
            };
            const visitorKey = 'visitor';
            localStorage.setItem(visitorKey, JSON.stringify(visitorData));
            
            let hour = currTime.getHours();
            let id = 1;
            if (0 <= hour && hour < 6) {
                id = 1;
            }
            else if (6 <= hour && hour < 15) {
                id = 2;
            }
            else if (15 <= hour && hour < 21) {
                id = 3;
            }
            else if (21 <= hour && hour < 24) {
                id = 4;
            }

            callApi("PUT", `./server/api/api_stats.php/uniqueUser/${id}`);

        } 
        else {
            console.error('Failed to retrieve IP address');
        }
    }

    storeVisitorInfo();

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

async function callApi(method, url, data = []) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    };

    try {
        const response = await fetch(url, options);
        if (!response.ok) {
            throw new Error('Network response was not ok, code:' + response.statusText);
        }
        const responseData = await response.json();
        return responseData; // Return the JSON data
    } catch (error) {
        console.error('Error:', error);
        throw error; // Re-throw the error to be caught by the caller
    }
}

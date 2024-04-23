const btnGetInfo = document.getElementById("btn-info");
const divPlace = document.getElementById("place-content");
const lblPlace = document.getElementById("place");
const imgflag = document.getElementById("flag");
const lblCurTmp = document.getElementById("cur-temp");
const lblAvgTmp = document.getElementById("avg-temp");
const lblCountry = document.getElementById("country");
const lblCapital = document.getElementById("capital");
const lblCurrency = document.getElementById("currency");

const errPlace = document.getElementById("err-arrival-place");
const errDate = document.getElementById("err-arrival-date");

btnGetInfo.addEventListener("click", getInfo);

async function getInfo() {
    let location = formatTown(document.getElementById("arrival-place").value);
    let arrivalDate = document.getElementById("arrival-date").value;
    

    if (location.trim() === "") {
        errPlace.classList.remove("hidden");
        errPlace.textContent = "Zadaj destináciu/miesto";
    }
    else if (arrivalDate.trim() === "") {
        errDate.classList.remove("hidden");
        errDate.textContent = "Zadaj dátum";
    }
    else {
        errDate.classList.add("hidden");
        errPlace.classList.add("hidden");
        let fromDate = getOldDate(arrivalDate, -1, 0);
        let toDate = getOldDate(arrivalDate, -1, 7);
        let data = await getWeatherData(location, fromDate, toDate);
        showPlace(data);
    }
    
}

async function getWeatherData(location, fromDate, todate) {
    let data = "";
    try {
        const response = await fetch(`./server/api_weather.php?location=${location}&fromDate=${fromDate}&toDate=${todate}`);
        data = await response.json();
    } catch (error) {
        console.error('Error:', error);
    }
    return data;
};

function getOldDate(date, yearOffset = 0, dayOffset = 0) {
    let dateObject = new Date(date);

    dateObject.setDate(dateObject.getDate() + dayOffset);
    dateObject.setFullYear(dateObject.getFullYear() + yearOffset);

    // Format the modified date as YYYY-MM-DD
    var year = dateObject.getFullYear();
    var month = String(dateObject.getMonth()+1).padStart(2, '0');
    var day = String(dateObject.getDate()).padStart(2, '0');
    var modifiedDate = year + "-" + month + "-" + day;

    return modifiedDate;
}

function formatTown(town) {
    let normalizedTown = town.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    return normalizedTown.replace(/[^a-zA-Z0-9]+/g, '-').toLowerCase();
}

function showPlace(data) {
    lblPlace.textContent = data.location.name + " /";
    imgflag.src = data.flags.svg;
    lblCurTmp.textContent = "Aktuálna teplota / " + data.current.temp_c + "°C / " + data.current.condition.text;
    lblAvgTmp.textContent = "Priemerná teplota pre obdobie (predošlý rok) / " + countDailyAvg(data.forecast.forecastday) + "°C";
    lblCountry.textContent = "Štát / " + data.location.country;
    lblCapital.textContent = "Hlavné mesto / " + data.capital[0];

    const currencyCode = Object.keys(data.currencies)[0];

    lblCurrency.textContent = "Mena / " + data.currencies[currencyCode].name + " / 1 € = " + data.eur[currencyCode.toLowerCase()] + " "+ data.currencies[currencyCode].symbol;

    divPlace.classList.remove("hidden");
}

function countDailyAvg(daysArray) {
    let totalTemp = 0;
    let daysAmount = 0;

    daysArray.forEach(day => {
        totalTemp += day.day.avgtemp_c;
        daysAmount ++;
    });

    return Math.round((totalTemp / daysAmount) * 100) / 100;
}

function findCurrencyRate() {

}
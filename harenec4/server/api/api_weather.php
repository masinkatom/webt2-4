<?php
// Your Weather API key

require_once 'api_keys.php';

// Get location from frontend
$location = isset($_GET['location']) ? $_GET['location'] : '';
$dateFrom = isset($_GET['fromDate']) ? $_GET['fromDate'] : '';
$dateTo = isset($_GET['toDate']) ? $_GET['toDate'] : '';

// Call getWeatherData function with location
$weatherData = getWeatherData($location, $dateFrom, $dateTo);

// Return JSON response
header('Content-Type: application/json');

echo json_encode($weatherData);


// Function to fetch weather data from Weather API
function getWeatherData($location, $dateFrom, $dateTo) {
    global $apiKeyWeatherApi;
    global $apiKeyVisualCrossing;
    
    // Construct the API URL
    $apiUrlCurrent = "https://api.weatherapi.com/v1/current.json?key={$apiKeyWeatherApi}&q={$location}&lang=sk";
    $dataCurrent = json_decode(curling($apiUrlCurrent), true);
    $country = str_replace(' ', '%20', $dataCurrent["location"]["country"]);

    $apiUrlHistory = "https://api.weatherapi.com/v1/history.json?key={$apiKeyWeatherApi}&q={$location}&dt={$dateFrom}&end_dt={$dateTo}&lang=sk";
    $dataHistory = json_decode(curling($apiUrlHistory), true);

    $apiUrlCountry = "https://restcountries.com/v3.1/name/{$country}?fields=flags,capital,currencies";
    $dataCountry = json_decode(curling($apiUrlCountry), true)[0];

    $apiUrlCurrency = "https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/eur.json";
    $dataCurrency = json_decode(curling($apiUrlCurrency), true);

    

    $data = array_merge($dataCurrent, $dataHistory, $dataCountry, $dataCurrency);

    return $data;
}

function curling($apiUrl) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $responseCurrent = curl_exec($ch);
    curl_close($ch);

    return $responseCurrent;
}
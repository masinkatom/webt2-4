<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../config4.php';
require_once '../place.php';
require_once '../stat.php';
// Create an instance of the Place class
$place = new Place($pdo);
$stat = new Stat($pdo);
// Get the request method
$method = $_SERVER['REQUEST_METHOD'];
// Get the requested endpoint
$endpoint = getEndpoint($_SERVER['QUERY_STRING']);

// Set the response content type
header('Content-Type: application/json');
// Process the request
switch ($method) {
    case 'GET':
        if ($endpoint === '/places') {
            $place = $place->getAllPlaces();
            echo json_encode($place, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } 
        elseif (preg_match('/^\/place\/(\d+)$/', $endpoint, $matches)) {
            // Get place by ID
            $placeId = $matches[1];
            $place = $place->getPlaceById($placeId);
            if (empty($place)) {
                http_response_code(404); // Not Found
                echo json_encode(['error' => 'Place not found']);
                exit;
            }
            echo json_encode($place, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } 
        elseif (preg_match('/^\/place\/([^\/]+)$/', $endpoint, $matches)) {
            // Get place by place name
            $placeName = $matches[1];
            $placeId = $place->getIdByName($placeName);
            if (empty($placeId)) {
                http_response_code(404); // Not Found
                echo json_encode(['error' => 'Place not found']);
                exit;
            }
            $place = $place->getPlaceById($placeId);
            echo json_encode($place, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        break;
    case 'POST':
        if ($endpoint === '/place') {
            // Add new place
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $place->addPlace($data);
            echo json_encode(['success' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;
    case 'PUT':
        if ($endpoint === '/place') {
            // Update place search amount
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $place->updatePlaceSearchedAmount($data);
            echo json_encode(['success' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        elseif (preg_match('/^\/uniqueUser\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];
            $result = $stat->updateStat($id);
            echo json_encode(['success' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;
    case 'DELETE':
        if (preg_match('/^\/place\/(\d+)$/', $endpoint, $matches)) {
            // Delete timetableAction by ID
            $placeId = $matches[1];
            $result = $place->deletePlace($placeId);
            echo json_encode(['success' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;
}

function getEndpoint($link) {
    return "/" . rtrim(explode("/", $link, 6)[5], '/');
}
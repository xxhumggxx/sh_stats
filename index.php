<?php
header("Access-Control-Allow-Origin: https://vanhungbui.store");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$dataFile = "data.json";

if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $newData = json_decode($input, true);
    
    if ($newData && isset($newData['executor'], $newData['player'], $newData['usageTime'], $newData['timestamp'])) {
        $data = json_decode(file_get_contents($dataFile), true);
        
        $found = false;
        foreach ($data as &$entry) {
            if ($entry['player'] === $newData['player']) {
                $entry = $newData;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $data[] = $newData;
        }
        
        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode(["status" => "success", "message" => "Data saved successfully"]);
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid data"]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($dataFile)) {
        $data = file_get_contents($dataFile);
        echo $data;
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Data file not found"]);
    }
    exit;
}

http_response_code(405);
echo json_encode(["status" => "error", "message" => "Method not allowed"]);
?>

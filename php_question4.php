/*
Error Handling & Logging
A third-party API returns unpredictable JSON responses, sometimes with missing fields. How would you handle parsing it in PHP to prevent errors?
*/

<?php
function fetchDataFromApi($url) {
    $response = file_get_contents($url);

    if ($response === false) {
        error_log("API request failed: $url");
        return null;
    }

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error: " . json_last_error_msg());
        return null;
    }

    return $data;
}

function processApiData($data) {
    if (!is_array($data)) {
        error_log("Invalid API response format.");
        return [];
    }

    return [
        'id' => $data['id'] ?? 'N/A',
        'name' => $data['name'] ?? 'Unknown',
        'email' => $data['email'] ?? 'No Email',
    ];
}

// Example Usage
$apiUrl = "https://api.example.com/user";
$apiResponse = fetchDataFromApi($apiUrl);

if ($apiResponse) {
    $user = processApiData($apiResponse);
    print_r($user);
} else {
    echo "Failed to retrieve data.";
}
?>
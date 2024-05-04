<?php
/************************************CONFIG****************************************/
// DATABASE DETAILS
$DB_ADDRESS = "mahadev.mysql.database.azure.com";
$DB_USER = "mahadev"; // Replace with your database username
$DB_PASS = "Rahul.123"; // Replace with your database password
$DB_NAME = "mainmahadev"; // Replace with your database name

// API KEY
$API_KEY = "Rahul.a08321"; // Replace with your API key
/************************************CONFIG****************************************/

// Get the phone number and API key from the request
$apiKey = $_POST['key'];
$phoneNumber = $_POST['phone'];

// Set content type to plain text
header('Content-type: text/plain');

if (isset($apiKey) && isset($phoneNumber)) {
    if ($apiKey === $API_KEY) {
        // Connect to the database
        $conn = new mysqli($DB_ADDRESS, $DB_USER, $DB_PASS, $DB_NAME);

        // Check for connection errors
        if ($conn->connect_error) {
            header("HTTP/1.0 500 Internal Server Error");
            echo "ERROR Database Connection Failed: " . $conn->connect_error;
        } else {
            // Check if the phone number already exists
            $checkQuery = "SELECT COUNT(*) AS count FROM Accounts WHERE phone = '$phoneNumber'";
            $result = $conn->query($checkQuery);

            // Check for query execution errors
            if ($result === false) {
                header("HTTP/1.0 500 Internal Server Error");
                echo "ERROR Query Execution Failed: " . $conn->error;
            } else {
                $row = $result->fetch_assoc();
                $count = $row['count'];

                // Return true if the phone number exists, false otherwise
                if ($count > 0) {
                    echo "true";
                } else {
                    echo "false";
                }
            }

            // Close the database connection
            $conn->close();
        }
    } else {
        // Invalid API key
        header("HTTP/1.0 400 Bad Request");
        echo "Bad Request";
    }
} else {
    // Missing parameters
    header("HTTP/1.0 400 Bad Request");
    echo "Bad Request";
}
?>

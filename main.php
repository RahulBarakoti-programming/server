<?php
/************************************CONFIG****************************************/
// DATABASE DETAILS
$DB_ADDRESS = "mahadev.mysql.database.azure.com";
$DB_USER = "mahadev";
$DB_PASS = "Rahul.123";
$DB_NAME = "mainmahadev";

// API KEY
$API_KEY = "Rahul.a08321";
/************************************CONFIG****************************************/

// Set content type to CSV
header('Content-type: text/csv');

if (isset($_POST['query']) && isset($_POST['key'])) {
    if ($_POST['key'] === $API_KEY) {
        $query = urldecode($_POST['query']);

        $conn = new mysqli($DB_ADDRESS, $DB_USER, $DB_PASS, $DB_NAME);

        if ($conn->connect_error) {
            header("HTTP/1.0 500 Internal Server Error");
            echo "ERROR Database Connection Failed: " . $conn->connect_error, E_USER_ERROR;
        } else {
            $result = $conn->query($query);

            if ($result === false) {
                header("HTTP/1.0 400 Bad Request");
                echo "Wrong SQL: " . $query . " Error: " . $conn->error, E_USER_ERROR;
            } else {
                if (strpos(strtoupper($query), 'SELECT') === 0) {
                    $csv = '';
                    while ($fieldinfo = $result->fetch_field()) {
                        $csv .= $fieldinfo->name . ",";
                    }
                    $csv = rtrim($csv, ",")."\n";
                    echo $csv;

                    $csv = '';
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()) {
                        foreach ($row as $key => $value) {
                            $csv .= $value . ",";
                        }
                        $csv = rtrim($csv, ",")."\n";
                    }
                    echo $csv;
                } else {
                    header("HTTP/1.0 201 Rows");
                    echo "AFFECTED ROWS: " . $conn->affected_rows;
                }
            }
            $conn->close();
        }
    } else {
        header("HTTP/1.0 400 Bad Request");
        echo "Bad Request";
    }
} else {
    header("HTTP/1.0 400 Bad Request");
    echo "Bad Request";
}
?>
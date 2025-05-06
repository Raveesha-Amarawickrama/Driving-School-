<?php

require_once 'config.php';

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Execute a query and return the results
 * 
 * @param string $sql The SQL query to execute
 * @return mysqli_result|bool Result of the query
 */
function executeQuery($sql) {
    global $conn;
    return $conn->query($sql);
}

/**
 * Get results as an associative array
 * 
 * @param string $sql The SQL query to execute
 * @return array Array of results
 */
function getResults($sql) {
    $result = executeQuery($sql);
    $data = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    return $data;
}

/**
 * Get a single row from the database
 * 
 * @param string $sql The SQL query to execute
 * @return array|null Single row of results or null
 */
function getRow($sql) {
    $result = executeQuery($sql);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

/**
 * Insert data into a table
 * 
 * @param string $table Table name
 * @param mixed $data Data to insert (associative array expected)
 * @return bool True on success, false on failure
 */


<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion absence";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the student ID from the AJAX request
$student_id = $_POST['student_id'];

// Delete the student data from the Eleves table
$sql = "DELETE FROM Eleves WHERE id_eleve = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);

if ($stmt->execute()) {
    http_response_code(200); // Success
} else {
    http_response_code(500); // Internal Server Error
}

$stmt->close();
$conn->close();
?>

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

// Get the student ID and absence count from the AJAX request
$student_id = $_POST['student_id'];
$absence_count = $_POST['absence_count'];

// Update the absence count in the Eleves table
$sql = "UPDATE Eleves SET nb_absence = ? WHERE id_eleve = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $absence_count, $student_id);

if ($stmt->execute()) {
    http_response_code(200); // Success
} else {
    http_response_code(500); // Internal Server Error
}

$stmt->close();
$conn->close();
?>

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

// Get the student data from the AJAX request
$student_data = json_decode(file_get_contents('php://input'), true);

// Insert the student data into the Eleves table
$sql = "INSERT INTO Eleves (nom, prenom, date_naissence, nb_absence) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $student_data['lastName'], $student_data['firstName'], $student_data['dateOfBirth'], $student_data['absenceCount']);

if ($stmt->execute()) {
    http_response_code(200); // Success
} else {
    http_response_code(500); // Internal Server Error
}

$stmt->close();
$conn->close();
?>

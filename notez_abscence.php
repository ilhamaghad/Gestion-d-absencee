<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notez abscence</title>
    <link rel="stylesheet" href="styl1.css">
</head>
<body class="body-absc">
<div class="labels">
    <br>
    <h3 id="name"> Nom : </h3>
    <label id="label3" for="input1"></label> 
    <input id="input1" type="text" name="nom" placeholder=""><br><br>
    <h3 id="lastname"> Prenom : </h3> 
    <label id="label4" for="input2"></label> 
    <input id="input2" type="text" name="Prenom" placeholder=""><br><br>
    <h3 id="date"> Date Naissance : </h3> 
    <label id="label5" for="input3"></label>
    <input id="input3" type="date" id="dateNaissance" name="dateNaissance">
    <button id="butt" onclick="addStudent()">Enregistrer</button>
</div>
<h2 id="list" >Students List</h2>
<!-- Tableau pour afficher la liste des étudiants -->
<table id="studentTable">
  <thead>
    <tr>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Date de naissance</th>
      <th>Présence</th>
      <th>Absence</th>
      <th>Delete</th>
      <th>Nombre d'absences</th>
    </tr>
  </thead>
  <tbody id="studentList">
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

    // Get students from database
    $sql_students = "SELECT id_eleve, nom, prenom, date_naissence, nb_absence FROM eleves";
    $result_students = $conn->query($sql_students);

    if ($result_students->num_rows > 0) {
        // Output data of each row
        while($row_student = $result_students->fetch_assoc()) {
            $student_id = $row_student['id_eleve'];
            $student_name = $row_student['nom'];
            $student_last_name = $row_student['prenom'];
            $date_of_birth = $row_student['date_naissence'];
            $num_absences = $row_student['nb_absence'];

            echo "<tr data-student-id='$student_id'>";
            echo "<td>$student_name</td>";
            echo "<td>$student_last_name</td>";
            echo "<td>$date_of_birth</td>";
            echo "<td><button onclick='markAttendance($student_id, \"present\", this.parentNode.parentNode)'>P</button></td>";
            echo "<td><button onclick='markAttendance($student_id, \"absent\", this.parentNode.parentNode)'>A</button></td>";
            echo "<td><button onclick='deleteStudent($student_id)'>Delete</button></td>";
            echo "<td>$num_absences</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>0 results</td></tr>";
    }
    $conn->close();
    ?>
  </tbody>
</table>

<script>
function addStudent() {
    const firstName = document.getElementById('input1').value.trim();
    const lastName = document.getElementById('input2').value.trim();
    const date = document.getElementById('input3').value;

    if (firstName === '' || lastName === '' || date === '') {
        alert('Please enter all fields.');
        return;
    }

    const studentList = document.getElementById('studentList');
    const tr = document.createElement('tr');
    const studentId = Date.now(); // Generate a unique ID for the student

    // Cellule pour le nom
    const nomCell = document.createElement('td');
    nomCell.textContent = firstName;
    tr.appendChild(nomCell);

    // Cellule pour le prénom
    const prenomCell = document.createElement('td');
    prenomCell.textContent = lastName;
    tr.appendChild(prenomCell);

    // Cellule pour la date de naissance
    const dateCell = document.createElement('td');
    dateCell.textContent = date;
    tr.appendChild(dateCell);

    // Cellule pour le bouton de présence
    const presentCell = document.createElement('td');
    const presentButton = document.createElement('button');
    presentButton.textContent = 'P';
    presentButton.onclick = () => markAttendance(studentId, 'present', tr);
    presentCell.appendChild(presentButton);
    tr.appendChild(presentCell);

    // Cellule pour le bouton d'absence
    const absentCell = document.createElement('td');
    const absentButton = document.createElement('button');
    absentButton.textContent = 'A';
    absentButton.onclick = () => markAttendance(studentId, 'absent', tr);
    absentCell.appendChild(absentButton);
    tr.appendChild(absentCell);
    
    const deleteCell = document.createElement('td');
    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Delete';
    deleteButton.onclick = () => deleteStudent(studentId);
    deleteCell.appendChild(deleteButton);
    tr.appendChild(deleteCell);

    // Cellule pour le nombre d'absences
    const absenceCountCell = document.createElement('td');
    absenceCountCell.textContent = 0;
    tr.appendChild(absenceCountCell);

    studentList.appendChild(tr);

    // Store student data in the database
    storeStudentData(studentId, firstName, lastName, date, 0);

    document.getElementById('input1').value = '';
    document.getElementById('input2').value = '';
    document.getElementById('input3').value = '';
}

function markAttendance(studentId, status, row) {
    const absenceCountCell = row.cells[6];
    let absenceCount = parseInt(absenceCountCell.textContent);

    if (status === 'present') {
        row.style.color = 'green';
        updateStudentData(studentId, 'presence', true);
    } else if (status === 'absent') {
        row.style.color = 'red';
        absenceCount++;
        absenceCountCell.textContent = absenceCount;

        // Send an AJAX request to update the absence count in the database
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_absence.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log('Absence count updated successfully');
            } else {
                console.error('Error updating absence count');
            }
        };
        xhr.send(`student_id=${studentId}&absence_count=${absenceCount}`);
    }
}


function deleteStudent(studentId) {
    const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
    if (row) {
        // Send an AJAX request to delete the student data from the database
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_student.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log('Student data deleted successfully');
                // Remove the row from the table
                row.remove();
            } else {
                console.error('Error deleting student data');
            }
        };
        xhr.send(`student_id=${studentId}`);
    }
}


function storeStudentData(studentId, firstName, lastName, dateOfBirth, absenceCount) {
    const student = {
        id: studentId,
        firstName,
        lastName,
        dateOfBirth,
        absenceCount
    };

    // Send the student data to the server using AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'insert_student.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            console.log('Student data inserted successfully');
        } else {
            console.error('Error inserting student data');
        }
    };
    xhr.send(JSON.stringify(student));
}



function updateStudentData(studentId, field, value) {
    // Here, you would update the student data on the server using an AJAX request or a form submission
    // For example, you could use the Fetch API or XMLHttpRequest to send a PUT or PATCH request to a PHP script
    // to update the corresponding field in the database for the given student ID
}

function deleteStudentData(studentId) {
    // Here, you would delete the student data from the server using an AJAX request or a form submission
    // For example, you could use the Fetch API or XMLHttpRequest to send a DELETE request to a PHP script
    // to delete the student record from the database based on the student ID
}
</script>
</body>
</html>

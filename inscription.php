
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="styl1.css">
</head>
<body id="bdy">
    <div class="prof">
        <a href=""><img id="teacher"  src="teacher.png" alt=""></a>
    </div>
    <h1 id="txt"> Espace Professeur </h1>
    <form  id="input" method="post">
        <label for="label1"></label> 
        <input id="label1" type="text" name="nom" placeholder="Nom"><br><br>
        <label for="label2"></label> 
        <input id="label2" type="text" name="prenom" placeholder="Prenom"><br><br>
        <label for="label2"></label> 
        <input id="label8" type="password" name="password" placeholder="Password"><br><br>
        <div id="input6"> 
            <div class="radio-group">
                <input id="option1" type="radio" name="module">
                <label for="option1">Développement Web</label><br>
            </div>
            <div class="radio-group">
                <input id="option2" type="radio" name="module">
                <label for="option2">Systeme d'information</label><br>
            </div>
            <div class="radio-group">
                <input id="option3" type="radio" name="module">
                <label for="option3">Systeme d'exploitation</label><br>
            </div>
            <div class="radio-group">
                <input id="option4" type="radio" name="module">
                <label for="option4">Base de donne</label><br>
            </div>
            <div class="radio-group">
                <input id="option5" type="radio" name="module">
                <label for="option5">Programmation avancées</label>
            </div>
        </div> 
        <br><br>
        <button class="button" onclick="redirectToPages()" >se connecter</button>
        <!-- <a href="notez_abscence.html" class="button">Se connecter</a> -->
    </form> 
    <script>
        function redirectToPages() {
    window.location.href = "inscription.php"; // Link to your PHP page
    window.open("notez_abscence.html", "_blank"); // Open another page (HTML) in a new tab
}

    </script>
    
</body>
</html>

<?php
// Mot de passe attendu
$expected_password = "web12345";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password = $_POST['password'];

    // Vérifier si le mot de passe est correct
    if ($password === $expected_password) {
        // Rediriger vers la page HTML
        header("Location: notez_abscence.html");
        exit; // Assurez-vous d'arrêter l'exécution du script après la redirection
    } else {
        // Mot de passe incorrect, afficher un message d'erreur
        
        echo "<script>alert('Mot de passe incorrect. Veuillez réessayer.');</script>";

    }
}

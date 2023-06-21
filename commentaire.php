<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirection vers la page de connexion
    header("Location: connexion.php");
    exit;
}
 

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer le commentaire du formulaire
    $commentaire = $_POST["commentaire"];
    
    // Connexion à la base de données
    $host = "localhost";
    $dbname = "livreor";
    $username = "root";
    $passwordDB = "Etoile19*";

    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwordDB);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Insérer le commentaire dans la table commentaires
        $query = "INSERT INTO commentaires (commentaire, id_utilisateur, date) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->execute([$commentaire, $_SESSION["login"]]);
        
        // Redirection vers la page du livre d'or
        header("Location: livre-or.php");
        exit;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="css/commentaire.css" rel="stylesheet"/>
    <title>Ajouter un commentaire</title>
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <h2>Ajouter <br>un commentaire</h2>
                    
                    <div class="inputbox">
                        <textarea id="commentaire" name="commentaire" required placeholder="Commentaire" cols="43" rows="7.5"></textarea>
                    </div>
                    
                    <div class="button">
                        <input type="submit" value="Poster le commentaire">
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>







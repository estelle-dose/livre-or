<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
$loggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "livreor";

$conn = new mysqli($servername, $username, $password_db, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Récupérer tous les commentaires du livre d'or, triés du plus récent au plus ancien
$sql = "SELECT commentaire, id_utilisateur, date FROM commentaires ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<link href="css/livre-or.css" rel="stylesheet"/>
    <title>Livre d'or</title>
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
            <br><h2>Livre d'or</h2><br>
            
            <?php
            // Afficher tous les commentaires
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $commentaire = $row["commentaire"];
                    $id_utilisateur = $row["id_utilisateur"];
                    $date = date("d/m/Y", strtotime($row["date"]));

                    // Récupérer le login de l'utilisateur qui a posté le commentaire
                    $login = $id_utilisateur;
                    if ($id_utilisateur != 0) {
                        $getUserSql = "SELECT login FROM utilisateurs WHERE id = $id_utilisateur";
                        $userResult = $conn->query($getUserSql);
                        if ($userResult && $userResult->num_rows > 0) {
                            $userRow = $userResult->fetch_assoc();
                            $login = $userRow["login"];
                        }
                    }

                    // Afficher le commentaire
                    echo '<div class="comment">';
                    echo '<div class="comment-date">Posté le ' . $date . '</div>';
                    echo '<div class="comment-user">Par ' . $login . '</div>';
                    echo '<div class="comment-content">' . $commentaire . '</div>' . '<br>';
                    echo '</div>';
                }
            } else {
                echo "<p>Aucun commentaire pour le moment.</p>";
            }

            // Afficher le lien vers la page d'ajout de commentaire si l'utilisateur est connecté
            if ($loggedIn) {
                echo '<button><a href="commentaire.php">Ajouter un commentaire</a></button>';
            }

            // Fermer la connexion à la base de données
            $conn->close();
            ?>
            </div>
        </div>
    </section>
</body>
</html>

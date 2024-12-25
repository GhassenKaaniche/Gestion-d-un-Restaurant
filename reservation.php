<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $table_id = $_POST['table_id'];
    $personnes = $_POST['personnes'];

    // Vérification des conditions
    $sql = "SELECT * FROM tables WHERE ID = $table_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $table = $result->fetch_assoc();

        // Vérification de la disponibilité de la table et de la capacité
        if ($table['statut'] == 'disponible' && $table['capacite'] >= $personnes) {
            // Vérification si le client existe déjà
            $sql_client = "SELECT * FROM clients WHERE email = '$email'";
            $client_result = $conn->query($sql_client);

            if ($client_result->num_rows > 0) {
                // Le client existe déjà, on récupère son ID
                $client = $client_result->fetch_assoc();
                $client_id = $client['ID'];
            } else {
                // Le client n'existe pas, on l'ajoute
                $sql_insert_client = "INSERT INTO clients (nom, email, telephone) VALUES ('$nom', '$email', '$telephone')";
                if ($conn->query($sql_insert_client) === TRUE) {
                    // Récupération du dernier ID du client inséré
                    $client_id = $conn->insert_id;
                } else {
                    $message = "Erreur lors de l'ajout du client.";
                    echo $message;
                    exit();
                }
            }

            // Réservation de la table
            $sql_update = "UPDATE tables SET statut = 'réservée' WHERE ID = $table_id";
            if ($conn->query($sql_update) === TRUE) {
                // Insertion de la réservation dans la base de données
                $sql_reservation = "INSERT INTO reservations (client_id, table_id, date, heure, statut) 
                                    VALUES ($client_id, $table_id, '$date', '$time', 'confirmée')";
                if ($conn->query($sql_reservation) === TRUE) {
                    $message = "Réservation réussie !";
                } else {
                    $message = "Erreur lors de l'enregistrement de la réservation.";
                }
            } else {
                $message = "La table est déjà réservée.";
            }
        } else {
            $message = "La table est déjà réservée ou la capacité est insuffisante.";
        }
    } else {
        $message = "Table non trouvée.";
    }

    echo $message;
}

$conn->close();
?>
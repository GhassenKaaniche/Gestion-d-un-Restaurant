<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "restaurant");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérification si les données sont envoyées par le formulaire
if (isset($_POST['nom'], $_POST['telephone'], $_POST['email'], $_POST['plat'], $_POST['quantite'], $_POST['date'])) {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $plat_id = $_POST['plat'];
    $quantite = $_POST['quantite'];
    $date = $_POST['date'];

    // Vérification si le client existe
    $sql = "SELECT ID FROM clients WHERE nom = ? AND telephone = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nom, $telephone, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Client existant
        $client_id = $result->fetch_assoc()['ID'];
    } else {
        // Nouveau client
        $stmt = $conn->prepare("INSERT INTO clients (nom, telephone, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $telephone, $email);
        $stmt->execute();
        $client_id = $stmt->insert_id;
    }

    // Insertion de la commande avec le statut par défaut "en cours de préparation"
    $stmt = $conn->prepare("INSERT INTO commandes (client_id, menu_id, quantite, statut) VALUES (?, ?, ?, 'en cours de préparation')");
    $stmt->bind_param("iii", $client_id, $plat_id, $quantite);
    $stmt->execute();

    echo "Commande passée avec succès !";
} else {
    echo "Certains champs sont manquants.";
}

$conn->close();
?>

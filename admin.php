<?php
session_start();

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "restaurant");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ajouter une table
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter_table'])) {
    $numero = $_POST['numero'];
    $capacite = $_POST['capacite'];

    $sql = "INSERT INTO tables (numero, capacite, statut) VALUES ('$numero', '$capacite', 'disponible')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Table ajoutée avec succès!</p>";
    } else {
        echo "Erreur : " . $conn->error;
    }
}

// Récupérer la liste des tables
$sql = "SELECT * FROM tables";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h1>Gestion des Tables</h1>

        <!-- Formulaire d'ajout de tables -->
        <form method="POST" class="form-ajout">
            <h2>Ajouter une Table</h2>
            <label for="numero">Numéro de la table :</label>
            <input type="number" id="numero" name="numero" required>

            <label for="capacite">Capacité :</label>
            <input type="number" id="capacite" name="capacite" required>

            <button type="submit" name="ajouter_table">Ajouter</button>
        </form>

        <!-- Liste des tables -->
        <h2>Liste des Tables</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Capacité</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['ID']}</td>
                            <td>{$row['numero']}</td>
                            <td>{$row['capacite']}</td>
                            <td>{$row['statut']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucune table trouvée</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "restaurant");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Suppression d'une réservation
if (isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id']; // Utilise la colonne ID de la table reservations

    // Récupérer table_id pour mettre à jour le statut
    $query = "SELECT table_id FROM reservations WHERE ID = $reservation_id";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $table_id = $row['table_id'];

        // Supprimer la réservation
        $conn->query("DELETE FROM reservations WHERE ID = $reservation_id");

        // Mettre à jour le statut de la table en "disponible"
        $conn->query("UPDATE tables SET statut = 'disponible' WHERE ID = $table_id");
    }
}

// Récupérer toutes les réservations
$reservations_query = "
    SELECT 
        reservations.ID, 
        reservations.date, 
        reservations.heure, 
        reservations.statut, 
        clients.nom AS client_nom, 
        tables.numero AS table_numero 
    FROM reservations 
    JOIN clients ON reservations.client_id = clients.ID 
    JOIN tables ON reservations.table_id = tables.ID";
$reservations_result = $conn->query($reservations_query);
?>

<div class="reservations-container">
    <h2>Liste des Réservations</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Table</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($reservation = $reservations_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $reservation['ID']; ?></td>
                    <td><?php echo htmlspecialchars($reservation['client_nom']); ?></td>
                    <td><?php echo $reservation['table_numero']; ?></td>
                    <td><?php echo $reservation['date']; ?></td>
                    <td><?php echo $reservation['heure']; ?></td>
                    <td><?php echo $reservation['statut']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['ID']; ?>">
                            <button type="submit" name="delete_reservation" class="btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "restaurant");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mise à jour du statut d'une commande
if (isset($_POST['update_order_status'])) {
    $order_id = $_POST['order_id']; // Utilise la colonne ID de la table commandes

    // Mettre à jour le statut en "livrée"
    $conn->query("UPDATE commandes SET statut = 'livrée' WHERE ID = $order_id");
}

// Récupérer toutes les commandes
$orders_query = "
    SELECT 
        commandes.ID, 
        commandes.quantite, 
        commandes.statut, 
        clients.nom AS client_nom, 
        menu.nom AS menu_nom 
    FROM commandes 
    JOIN clients ON commandes.client_id = clients.ID 
    JOIN menu ON commandes.menu_id = menu.ID";
$orders_result = $conn->query($orders_query);
?>

<div class="orders-container">
    <h2>Liste des Commandes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Plat</th>
                <th>Quantité</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orders_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $order['ID']; ?></td>
                    <td><?php echo htmlspecialchars($order['client_nom']); ?></td>
                    <td><?php echo htmlspecialchars($order['menu_nom']); ?></td>
                    <td><?php echo $order['quantite']; ?></td>
                    <td><?php echo $order['statut']; ?></td>
                    <td>
                        <?php if ($order['statut'] == 'en cours de préparation') { ?>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['ID']; ?>">
                                <button type="submit" name="update_order_status" class="btn-success">Marquer comme Livrée</button>
                            </form>
                        <?php } else { ?>
                            Livrée
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "restaurant");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ajouter un plat dans le menu
if (isset($_POST['add_menu_item'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $type = $_POST['type'];
    $image_path = $_POST['image_path'];

    $conn->query("INSERT INTO menu (nom, description, prix, type, image_path) VALUES ('$nom', '$description', $prix, '$type', '$image_path')");
}

// Modifier le prix d'un plat
if (isset($_POST['update_price'])) {
    $menu_id = $_POST['menu_id'];
    $new_price = $_POST['new_price'];

    $conn->query("UPDATE menu SET prix = $new_price WHERE ID = $menu_id");
}

// Récupérer tous les plats du menu
$menu_query = "SELECT * FROM menu";
$menu_result = $conn->query($menu_query);
?>

<div class="menu-container">
    <h2>Liste des Plats (Menu)</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Type</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($menu_item = $menu_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $menu_item['ID']; ?></td>
                    <td><?php echo htmlspecialchars($menu_item['nom']); ?></td>
                    <td><?php echo htmlspecialchars($menu_item['description']); ?></td>
                    <td><?php echo $menu_item['prix']; ?> €</td>
                    <td><?php echo htmlspecialchars($menu_item['type']); ?></td>
                    <td>
                        <?php if (!empty($menu_item['image_path'])) { ?>
                            <img src="<?php echo htmlspecialchars($menu_item['image_path']); ?>" alt="Image de <?php echo htmlspecialchars($menu_item['nom']); ?>" style="width: 100px; height: auto;">
                        <?php } else { ?>
                            Pas d'image
                        <?php } ?>
                    </td>
                    <td>
                        <!-- Formulaire pour modifier le prix -->
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="menu_id" value="<?php echo $menu_item['ID']; ?>">
                            <input type="number" name="new_price" min="0" step="0.01" placeholder="Nouveau Prix" required>
                            <button type="submit" name="update_price" class="btn-success">Modifier Prix</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="add-menu-item-container">
    <h2>Ajouter un Plat</h2>
    <form method="POST" class="add-menu-form">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required>
        
        <label for="description">Description :</label>
        <textarea name="description" id="description" rows="3" required></textarea>
        
        <label for="prix">Prix (€) :</label>
        <input type="number" name="prix" id="prix" min="0" step="0.01" required>
        
        <label for="type">Type :</label>
        <select name="type" id="type" required>
            <option value="entrée">Entrée</option>
            <option value="plat">Plat</option>
            <option value="dessert">Dessert</option>
        </select>
        
        <label for="image_path">URL de l'Image :</label>
        <input type="text" name="image_path" id="image_path" placeholder="images/plat.jpg" required>
        
        <button type="submit" name="add_menu_item" class="btn-add">Ajouter Plat</button>
    </form>
</div>
<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "restaurant");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ajouter un nouvel administrateur
if (isset($_POST['add_admin'])) {
    $admin_name = $_POST['admin_name'];
    $admin_password = $_POST['admin_password'];

    if (!empty($admin_name) && !empty($admin_password)) {
        // Insertion dans la table admin
        $conn->query("INSERT INTO admin (nom, mot_de_passe) VALUES ('$admin_name', '$admin_password')");
    }
}

// Supprimer un administrateur
if (isset($_POST['delete_admin'])) {
    $admin_id = $_POST['admin_id'];

    // Suppression de l'administrateur
    $conn->query("DELETE FROM admin WHERE ID = $admin_id");
}

// Récupérer la liste des administrateurs
$admins_query = "SELECT * FROM admin";
$admins_result = $conn->query($admins_query);
?>

<div class="admins-container">
    <h2>Gestion des Administrateurs</h2>

    <!-- Formulaire d'ajout d'un administrateur -->
    <form method="POST" class="add-admin-form">
        <h3>Ajouter un Administrateur</h3>
        <label for="admin_name">Nom :</label>
        <input type="text" name="admin_name" id="admin_name" required>
        <label for="admin_password">Mot de passe :</label>
        <input type="password" name="admin_password" id="admin_password" required>
        <button type="submit" name="add_admin" class="btn-success">Ajouter</button>
    </form>

    <!-- Liste des administrateurs -->
    <h3>Liste des Administrateurs</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($admin = $admins_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $admin['ID']; ?></td>
                    <td><?php echo htmlspecialchars($admin['nom']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="admin_id" value="<?php echo $admin['ID']; ?>">
                            <button type="submit" name="delete_admin" class="btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "restaurant");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer la liste des clients
$clients_query = "SELECT ID, nom, telephone, email FROM clients";
$clients_result = $conn->query($clients_query);
?>

<div class="clients-container">
    <h2>Liste des Clients</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($client = $clients_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $client['ID']; ?></td>
                    <td><?php echo htmlspecialchars($client['nom']); ?></td>
                    <td><?php echo htmlspecialchars($client['telephone']); ?></td>
                    <td><?php echo htmlspecialchars($client['email']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>









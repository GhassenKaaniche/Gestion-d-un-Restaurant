<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <title>Gestion de Restaurant</title>
</head>
<body>
<header>
    <a href="#" class="logo"><span>R</span>esto<span>Pro</span></a>
    <ul class="navbar">
        <li><a href="#banner">Accueil</a></li>
        <li><a href="#apropos">À Propos</a></li>
        <li><a href="#menu">Menu</a></li>
        <li><a href="#reservation">Réservations</a></li>
        <li><a href="#commande">Comamandes</a></li>
        <li><a href="#contact">Contact</a></li>  
    </ul>
    <a href="login.php" class="btn-admin">Admin Panel</a>
</header>

<section class="banner" id="banner">
    <div class="contenu">
        <h1>Bienvenue à <span>RestoPro</span></h1>
        <p>Découvrez nos plats préparés avec soin et savourez un moment unique.</p>
        <a href="#menu" class="btn">Voir le Menu</a>
        <a href="#reservation" class="btn secondary">Réserver</a>
    </div>
</section>

<section id="apropos" class="apropos">
    <div class="conteneur">
        <div class="texte">
            <h2>À Propos de Nous</h2>
            <p>
                RestoPro est un restaurant familial offrant une cuisine moderne et raffinée. Nos chefs expérimentés utilisent
                des ingrédients frais et locaux pour créer des plats délicieux. Que ce soit pour un dîner romantique, une sortie
                en famille ou un événement spécial, nous nous engageons à offrir une expérience mémorable.
            </p>
        </div>
        <div class="image">
        <img src="images/plat3.jpg" style="margin-right: 220px;" align="left" alt="Restaurant intérieur">
        </div>
    </div>
</section>

<section id="menu" class="menu">
    <h2>Notre Menu</h2>
    <div class="contenu">
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

        // Récupérer les plats avec leurs images
        $sql = "SELECT * FROM menu";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="plat">
                    <img src="' . $row['image_path'] . '" alt="' . $row['nom'] . '"style="width: 300px; height: 200px;">
                    <h3>' . $row['nom'] . '</h3>
                    <p>' . $row['description'] . '</p>
                    <p><strong>Prix : </strong>' . number_format($row['prix'], 2) . ' €</p>
                    <p><strong>Type : </strong>' . ucfirst($row['type']) . '</p>
                </div>';
            }
        } else {
            echo '<p>Le menu est en cours de mise à jour. Revenez bientôt !</p>';
        }

        $conn->close();
        ?>
    </div>
</section>
<section id="reservation" class="reservation">
    <h2>Réserver une Table</h2>
    <form method="POST" action="reservation.php">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telephone" placeholder="Téléphone" required>
        <input type="date" name="date" required>
        <input type="time" name="time" required>
        <input type="number" name="table_id" placeholder="Numéro de la table" required>
        <input type="number" name="personnes" placeholder="Nombre de personnes" required>
        <button type="submit">Envoyer</button>
    </form>
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
</section>

<section id="commande">
    <h2>Commandes en ligne</h2>
    <p>Simplifiez votre expérience en commandant vos plats préférés à emporter.</p>
    <form class="commande-form" method="POST" action="process_commande.php">
        <div>
            <label for="nom">Nom complet</label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
        </div>
        <div>
            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone" placeholder="Votre numéro de téléphone" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Votre email" required>
        </div>
        <div>
            <label for="plat">Sélectionnez un plat</label>
            <select id="plat" name="plat" required>
                <?php
                // Connexion à la base de données
                $conn = new mysqli("localhost", "root", "", "restaurant");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Récupération des plats depuis la table menu
                $result = $conn->query("SELECT ID, nom FROM menu");

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['ID'] . "'>" . $row['nom'] . "</option>";
                }

                $conn->close();
                ?>
            </select>
        </div>
        <div>
            <label for="quantite">Quantité</label>
            <input type="number" id="quantite" name="quantite" min="1" value="1" required>
        </div>
        <div>
            <label for="date">Date et Heure</label>
            <input type="datetime-local" id="date" name="date" required>
        </div>
        <button type="submit">Passer la commande</button>
    </form>
</section>

<section id="contact" class="contact">
    <h2>Contactez-Nous</h2>
    <p>Adresse : 123 Rue Gourmet, Ville Délicieuse</p>
    <p>Email : contact@restopro.com</p>
    <p>Téléphone : +123 456 789</p>
</section>

<footer>
    <p>&copy; 2024 RestoPro. Tous droits réservés.</p>
</footer>

</body>
</html>


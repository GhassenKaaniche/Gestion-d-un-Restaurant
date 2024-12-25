<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Connexion à la base de données
    $conn = new mysqli("localhost", "root", "", "restaurant");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Vérification des informations d'identification
    $sql = "SELECT * FROM admin WHERE nom = '$nom' AND mot_de_passe = '$mot_de_passe'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $nom;
        header('Location: admin.php');
    } else {
        echo "Nom ou mot de passe incorrect";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            position: relative;
        }

        h1::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background-color: #ff7e29;
            margin: 10px auto 0;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #ff7e29;
            color: #fff;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #e26b20;
        }

        .form-title span {
            width: 50px;
            height: 3px;
            background-color: #ff7e29;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h1>Admin Panel</h1>
        Nom: <input type="text" name="nom" required><br>
        Mot de passe: <input type="password" name="mot_de_passe" required><br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>




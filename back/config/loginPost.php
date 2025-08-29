<?php

$dsn = 'mysql:host=localhost;dbname=ecoride;charset=utf8mb4';
$username = 'ecoride_user';
$password = 'Nzp&pytq8sjGFMQEh9pH';

try{
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Réccupérer les données du formulaire de connection
    $emailForm = $_POST['email'];
    $passwordForm = $_POST['password'];

    //Récupérer les utilisateurs
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt =$pdo->prepare($query);
    $stmt->bindParam(':email', $emailForm);
    $stmt->execute();

    //Est ce que utilisateur existe
    if($stmt->rowCount() == 1){
        $monUser = $stmt->fetch(PDO::FETCH_ASSOC);
        if (hash('sha256', $passwordForm) === $monUser['password']) {
            echo "Connexion réussi ! Bienvenue" . $monUser['username'];
        }
        else{
            echo "Mot de passe incorrect";
        }
    }
    else{
        echo "Utilisateur introuvable, vérifier votre e-mail";
    }

}
catch (PDOException $e){
    echo "Erreur de connexion à la base de données : ". $e->getMessage();
}



?>
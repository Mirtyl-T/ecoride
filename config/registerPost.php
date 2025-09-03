<?php

$dsn = 'mysql:host=localhost;dbname=ecoride;charset=utf8mb4';
$username = 'ecoride_user';
$password = 'Nzp&pytq8sjGFMQEh9pH';

try {

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //Réccupérer les données du formulaire d'inscription
    $usernameForm = trim($_POST['username']);
    $emailForm = $_POST['email'];
    $passwordForm = $_POST['password'];


    //Vérifier l'unicité l'adresse mail
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt =$pdo->prepare($query);
    $stmt->bindParam(':email', $emailForm);
    $stmt->execute();

    //Est ce que utilisateur existe
    if($stmt->rowCount() > 0){
        die("Cette adresse mail est déjà utilisée");
    }

    // Hashage(encryptage)
    $hashedPassword = password_hash($passwordForm, PASSWORD_DEFAULT);

    //Insérer les données dans la base 
    $insertQuery= "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->bindParam(':username', $usernameForm);
    $stmt->bindParam(':email', $emailForm);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();


    echo "Inscription réussie";
}
catch (PDOException $e){
    echo "Erreur lors de l'inscription : ". $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>
        Inscription
    </h1>
    <form action="/back/config/registerPost.php" method="POST">
        <label for="username">Pseudo : </label>
        <input type="text" name="username" id="username" required /><br><br>

        <label for="email">Adresse e-mail : </label>
        <input type="email" name="email" id="email" required/><br><br>
        
        <label for="password">Mot de passe : </label>
        <input type="password" name="password" id="password" required/><br><br>

        <input type="submit" value="S'inscrire"/><br><br>


    </form>
</body>
</html>
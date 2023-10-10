<?php

function addUser(PDO $pdo, string $first_name, string $last_name, string $email, string $password, $role = "user")
{
    /*
        @todo_done faire la requête d'insertion d'utilisateur et retourner $query->execute();
        Attention faire une requête préparer et à binder les paramètres
    */
    $query = "INSERT INTO users (email, password, first_name, last_name, role)
              VALUES (:email, :password, :first_name, :last_name, :role)";
    $sql = $pdo->prepare($query);
    return $sql->execute(["email" => $email, "password" => password_hash($password, PASSWORD_DEFAULT), "first_name" => $first_name, "last_name" => $last_name, "role" => $role]);
}

function verifyUserLoginPassword(PDO $pdo, string $email, string $password)
{
    try {
        /*
            @todo_done faire une requête qui récupère l'utilisateur par email et stocker le résultat dans user
            Attention faire une requête préparer et à binder les paramètres
        */
        
        $query = "SELECT * FROM users WHERE email = '{$email}' ";
        $sql = $pdo->prepare($query);
        $sql->execute();
        
        $user = $sql->fetch(PDO::FETCH_ASSOC);

        if (empty($user)) {
            throw new Exception("User not found");
        }
        /*
            @todo_done Si on a un utilisateur et que le mot de passe correspond (voir fonction  native password_verify)
                alors on retourne $user
                sinon on retourne false
        */

        if (!empty($user["password"]) && !empty($password)) {
            if (password_verify($password, $user["password"])) {
                return $user;
            } else {
                throw new Exception("Incorrect password");
            }
        } else {
            throw new Exception("Missing password");
        }

    } catch (Exception $e) {
        echo "Error: <b>" . $e->getMessage() . "</b><br>";
    }


}

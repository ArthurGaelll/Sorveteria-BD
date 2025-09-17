<?php
$host = "localhost";
$db   = "loja";     // nome do banco que você criou
$user = "root";     // usuário do MySQL (normalmente root no XAMPP)
$pass = "";         // senha do MySQL (normalmente vazia no XAMPP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erro na conexão: " . $e->getMessage());
}
<?php
require_once "conexao.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $senha2 = $_POST['senha2'];

    if ($senha !== $senha2) {
        $erro = "As senhas não conferem.";
    } else {
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $erro = "Email já cadastrado.";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql2 = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'consumidor')";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([$nome, $email, $hash]);
            header("Location: login.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../css/style.css">
  <title>Cadastro</title>
</head>
<body>
  <div class="container">
    <h2>Cadastro de Usuário</h2>
    <?php if (!empty($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    <form method="POST">
      <input type="text" name="nome" placeholder="Nome" required><br><br>
      <input type="email" name="email" placeholder="Email" required><br><br>
      <input type="password" name="senha" placeholder="Senha" required><br><br>
      <input type="password" name="senha2" placeholder="Confirmar Senha" required><br><br>
      <button type="submit">Cadastrar</button>
    </form>
    <p>Já tem conta? <a href="login.php">Entrar</a></p>
  </div>
</body>
</html>
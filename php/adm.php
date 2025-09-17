<?php
require_once "auth.php";
verificarLogin("adm");
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../css/style.css">
  <title>Painel do ADM</title>
</head>
<body>
<div class="container">
  <h1>Painel Administrativo</h1>
  <p>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</p>

  <ul>
    <li><a href="produtos.php"><button>Gerenciar Produtos</button></a></li>
    <li><a href="vendas.php"><button>Gerenciar Vendas</button></a></li>
    <li><a href="logout.php"><button>Sair</button></a></li>
  </ul>
</div>
</body>
</html>
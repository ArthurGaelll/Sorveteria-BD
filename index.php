<?php
session_start();
require_once "php/conexao.php";

// Buscar produtos do banco
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id ASC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <title>Loja de Sorvetes</title>
</head>
<body>
<header>
  <h1>🍦 Loja de Sorvetes</h1>
  <nav>
    <?php if (isset($_SESSION['usuario_email'])): ?>
      <span>Olá, <?php echo $_SESSION['usuario_nome']; ?>!</span>
      <a href="php/logout.php"><button>Sair</button></a>
    <?php else: ?>
      <a href="php/login.php"><button>Login</button></a>
    <?php endif; ?>
  </nav>
</header>

<div class="container">
  <h2>Nossos Sorvetes</h2>

  <?php foreach ($produtos as $p): ?>
    <div class="card">
      <img src="imgs/<?php echo $p['imagem']; ?>" alt="<?php echo $p['nome']; ?>">
      <h3><?php echo $p['nome']; ?></h3>
      <p>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></p>

      <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'consumidor'): ?>
        <a href="php/consumidor.php?adicionar=<?php echo $p['id']; ?>">
          <button>Adicionar ao carrinho</button>
        </a>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

<div class="footer">
  <p>© 2025 Loja de Sorvetes</p>
</div>
</body>
</html>
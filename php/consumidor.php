<?php
require_once "auth.php";
require_once "conexao.php";
verificarLogin("consumidor");

// Inicializa carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Buscar produtos do banco
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id ASC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Adicionar produto ao carrinho
if (isset($_GET['adicionar'])) {
    $produto_id = $_GET['adicionar'];
    if (isset($_SESSION['carrinho'][$produto_id])) {
        $_SESSION['carrinho'][$produto_id]++;
    } else {
        $_SESSION['carrinho'][$produto_id] = 1;
    }
    header("Location: consumidor.php");
    exit;
}

// Remover produto do carrinho
if (isset($_GET['remover'])) {
    $produto_id = $_GET['remover'];
    if (isset($_SESSION['carrinho'][$produto_id])) {
        $_SESSION['carrinho'][$produto_id]--;
        if ($_SESSION['carrinho'][$produto_id] <= 0) {
            unset($_SESSION['carrinho'][$produto_id]);
        }
    }
    header("Location: consumidor.php");
    exit;
}

// Finalizar compra
if (isset($_POST['finalizar']) && !empty($_SESSION['carrinho'])) {
    if (!isset($_SESSION['usuario_id'])) {
        die("Erro: usuário não logado corretamente!");
    }

    $total = 0;

    foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
        $stmt = $pdo->prepare("SELECT preco FROM produtos WHERE id = ?");
        $stmt->execute([$produto_id]);
        $preco = $stmt->fetchColumn();
        $total += $preco * $quantidade;
    }

    // Inserir venda
    $stmt = $pdo->prepare("INSERT INTO vendas (usuario_id, total) VALUES (?, ?)");
    $stmt->execute([$_SESSION['usuario_id'], $total]);
    $venda_id = $pdo->lastInsertId();

    // Inserir itens da venda
    foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
        $stmt = $pdo->prepare("SELECT preco FROM produtos WHERE id = ?");
        $stmt->execute([$produto_id]);
        $preco = $stmt->fetchColumn();

        $stmt = $pdo->prepare("INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        $stmt->execute([$venda_id, $produto_id, $quantidade, $preco]);
    }

    // Limpar carrinho
    $_SESSION['carrinho'] = [];
    header("Location: consumidor.php?compra=sucesso");
    exit;
}

// Total de itens no carrinho
$total_itens = !empty($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/style.css">
    <title>Área do Cliente</title>
</head>
<body>
<div class="container">
    <h1>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</h1>

    <!-- Mensagem de compra realizada -->
    <?php if (isset($_GET['compra']) && $_GET['compra'] === 'sucesso'): ?>
        <p style="color:green;">Compra realizada com sucesso!</p>
    <?php endif; ?>

    <p>Total de produtos no carrinho: <?php echo $total_itens; ?></p>

    <!-- Produtos disponíveis -->
    <h2>Produtos Disponíveis</h2>
    <div class="produtos">
        <?php foreach ($produtos as $p): ?>
            <div class="card">
                <img src="../imgs/<?php echo $p['imagem']; ?>" alt="<?php echo $p['nome']; ?>">
                <h3><?php echo $p['nome']; ?></h3>
                <p>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></p>
                <a href="consumidor.php?adicionar=<?php echo $p['id']; ?>">
                    <button>Adicionar ao carrinho</button>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Carrinho -->
    <h2>Seu Carrinho</h2>
    <?php if (!empty($_SESSION['carrinho'])): ?>
        <ul>
            <?php foreach ($_SESSION['carrinho'] as $produto_id => $quantidade): ?>
                <?php 
                $key = array_search($produto_id, array_column($produtos, 'id'));
                $produto = $produtos[$key]; 
                ?>
                <li>
                    <?php echo $produto['nome']; ?> - <?php echo $quantidade; ?> unidade(s) 
                    <a href="consumidor.php?remover=<?php echo $produto_id; ?>"><button>Remover</button></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <form method="POST">
            <button type="submit" name="finalizar">Comprar</button>
        </form>
    <?php else: ?>
        <p>Carrinho vazio.</p>
    <?php endif; ?>

    <a href="../index.php"><button>Voltar à Loja</button></a> 
    <a href="logout.php"><button>Sair</button></a>
</div>
</body>
</html>

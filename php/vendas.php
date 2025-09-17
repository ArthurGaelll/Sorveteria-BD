<?php
require_once "auth.php";
require_once "conexao.php";
verificarLogin("adm");

// Buscar todas as vendas
$stmt = $pdo->query("
    SELECT v.id AS venda_id, u.nome AS cliente, v.total 
    FROM vendas v
    JOIN usuarios u ON v.usuario_id = u.id
    ORDER BY v.id DESC
");
$vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/style.css">
    <title>Gerenciar Vendas</title>
</head>
<body>
<div class="container">
    <h1>Gerenciar Vendas</h1>
    <a href="adm.php"><button>Voltar</button></a>

    <?php if(empty($vendas)): ?>
        <p>Nenhuma venda registrada.</p>
    <?php else: ?>
        <?php foreach ($vendas as $venda): ?>
            <div class="venda">
                <h3>
                    Venda #<?php echo $venda['venda_id']; ?> - Cliente: <?php echo $venda['cliente']; ?> - Total: R$ <?php echo number_format($venda['total'],2,',','.'); ?>
                </h3>
                <ul>
                <?php
                $stmt = $pdo->prepare("
                    SELECT p.nome, i.quantidade, i.preco_unitario 
                    FROM itens_venda i 
                    JOIN produtos p ON i.produto_id = p.id 
                    WHERE i.venda_id = ?
                ");
                $stmt->execute([$venda['venda_id']]);
                $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($itens as $item) {
                    echo "<li>{$item['nome']} - {$item['quantidade']} unidade(s) - R$ ".number_format($item['preco_unitario'],2,',','.')."</li>";
                }
                ?>
                </ul>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>

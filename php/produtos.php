<?php
require_once "auth.php";
require_once "conexao.php";
verificarLogin("adm");

// Remover produto
if (isset($_GET['remover'])) {
    $id = $_GET['remover'];
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: produtos.php");
    exit;
}

// Editar produto
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, preco = ? WHERE id = ?");
    $stmt->execute([$nome, $preco, $id]);
    header("Location: produtos.php");
    exit;
}

// Buscar todos os produtos
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id ASC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/style.css">
    <title>Gerenciar Produtos</title>
</head>
<body>
<div class="container">
    <h1>Gerenciar Produtos</h1>
    <a href="adm.php"><button>Voltar</button></a>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Imagem</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($produtos as $p): ?>
            <tr>
                <td><img src="../imgs/<?php echo $p['imagem']; ?>" width="50"></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                        <input type="text" name="nome" value="<?php echo $p['nome']; ?>" required>
                </td>
                <td>
                        <input type="number" step="0.01" name="preco" value="<?php echo $p['preco']; ?>" required>
                </td>
                <td>
                        <button type="submit" name="editar">Salvar</button>
                    </form>
                    <a href="produtos.php?remover=<?php echo $p['id']; ?>"><button>Remover</button></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
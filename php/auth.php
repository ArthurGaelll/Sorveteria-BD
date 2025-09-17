<?php
session_start();

function verificarLogin($tipo) {
    if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== $tipo) {
        header("Location: login.php");
        exit;
    }
}
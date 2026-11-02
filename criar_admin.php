<?php
// Uso (no terminal, uma vez): php criar_admin.php "Nome" email@exemplo.com SenhaForte123
// Depois de criar o administrador, APAGUE este arquivo do servidor.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/config/config.php';

[$_, $nome, $email, $senha] = array_pad($argv, 4, '');
if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
    exit("Uso: php criar_admin.php \"Nome\" email@exemplo.com SenhaComMin8Chars\n");
}

$st = db()->prepare("INSERT INTO usuarios (nome, email, telefone, endereco, senha_hash, perfil) VALUES (?, ?, '16999999999', 'Administração do hospital', ?, 'admin')");
$st->execute([$nome, strtolower($email), password_hash($senha, PASSWORD_DEFAULT)]);
echo "Administrador criado.\n";

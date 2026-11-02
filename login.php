<?php
require_once __DIR__ . '/includes/functions.php';

if (usuario_atual()) {
    redirect('index.php');
}

$erro = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $senha = (string)($_POST['senha'] ?? '');

    // limite simples de tentativas por sessão
    $_SESSION['tentativas'] = $_SESSION['tentativas'] ?? 0;
    if ($_SESSION['tentativas'] >= 5 && time() < ($_SESSION['bloqueio'] ?? 0)) {
        $erro = 'Muitas tentativas. Aguarde um minuto e tente de novo.';
    } else {
        $st = db()->prepare('SELECT id, nome, perfil, senha_hash FROM usuarios WHERE email = ?');
        $st->execute([$email]);
        $u = $st->fetch();

        // compara sempre com um hash, mesmo sem usuário, para não revelar se o e-mail existe
        $hash = $u['senha_hash'] ?? '$2y$10$usesomesillystringforsaltuYhJ5k1bq3mN2cX0m7mXQ1zP7vL0Zq6';
        if ($u && password_verify($senha, $hash)) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = ['id' => (int)$u['id'], 'nome' => $u['nome'], 'perfil' => $u['perfil']];
            unset($_SESSION['tentativas'], $_SESSION['bloqueio']);
            redirect($u['perfil'] === 'admin' ? 'admin/index.php' : 'index.php');
        }
        $_SESSION['tentativas']++;
        if ($_SESSION['tentativas'] >= 5) {
            $_SESSION['bloqueio'] = time() + 60;
            $_SESSION['tentativas'] = 5;
        }
        $erro = 'E-mail ou senha incorretos.';
    }
}

$titulo = 'Entrar';
require __DIR__ . '/includes/header.php';
?>
<div class="form-box">
  <h1>Entrar</h1>
  <?php if ($erro): ?><div class="alerta alerta-erro" role="alert"><?= e($erro) ?></div><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <label for="email">E-mail</label>
    <input id="email" name="email" type="email" required value="<?= e($email) ?>" autocomplete="email">
    <label for="senha">Senha</label>
    <input id="senha" name="senha" type="password" required autocomplete="current-password">
    <button type="submit" class="btn">Entrar</button>
  </form>
  <p>Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

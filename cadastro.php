<?php
require_once __DIR__ . '/includes/functions.php';

if (usuario_atual()) {
    redirect('index.php');
}

$erros = [];
$d = ['nome' => '', 'email' => '', 'telefone' => '', 'endereco' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$erros, $d] = validar_usuario($_POST, true);
    if (($d['senha'] ?? '') !== (string)($_POST['confirmar'] ?? '')) {
        $erros[] = 'As senhas não coincidem.';
    }
    if (!$erros) {
        $st = db()->prepare(
            "INSERT INTO usuarios (nome, email, telefone, endereco, senha_hash, perfil) VALUES (?, ?, ?, ?, ?, 'paciente')"
        );
        $st->execute([$d['nome'], $d['email'], $d['telefone'], $d['endereco'], password_hash($d['senha'], PASSWORD_DEFAULT)]);
        flash('ok', 'Cadastro realizado! Faça login para continuar.');
        redirect('login.php');
    }
}

$titulo = 'Cadastro';
$scripts = ['geo.js'];
require __DIR__ . '/includes/header.php';
?>
<div class="form-box">
  <h1>Criar conta</h1>
  <p class="pequeno">Pedimos apenas os dados necessários para o atendimento.</p>
  <?php erros_show($erros); ?>
  <form method="post" novalidate>
    <?= csrf_field() ?>
    <label for="nome">Nome completo</label>
    <input id="nome" name="nome" type="text" maxlength="100" required value="<?= e($d['nome']) ?>" autocomplete="name">

    <label for="email">E-mail</label>
    <input id="email" name="email" type="email" maxlength="120" required value="<?= e($d['email']) ?>" autocomplete="email">

    <label for="telefone">Telefone / WhatsApp</label>
    <input id="telefone" name="telefone" type="tel" data-mascara="telefone" required value="<?= e($d['telefone']) ?>" autocomplete="tel" placeholder="(16) 99999-9999">

    <label for="endereco">Endereço</label>
    <div class="campo-geo">
      <input id="endereco" name="endereco" type="text" maxlength="255" required value="<?= e($d['endereco']) ?>" autocomplete="street-address">
      <button type="button" id="btnLocal" class="btn btn-borda btn-sm">Usar minha localização</button>
    </div>
    <p class="pequeno" id="geoStatus" aria-live="polite">Opcional: com sua permissão, preenchemos o endereço pela sua localização atual (usa o serviço OpenStreetMap).</p>

    <label for="senha">Senha</label>
    <input id="senha" name="senha" type="password" minlength="8" required autocomplete="new-password">
    <p class="pequeno">Mínimo de 8 caracteres, com letras e números.</p>

    <label for="confirmar">Confirmar senha</label>
    <input id="confirmar" name="confirmar" type="password" required autocomplete="new-password">

    <button type="submit" class="btn">Cadastrar</button>
  </form>
  <p>Já tem conta? <a href="login.php">Entrar</a></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

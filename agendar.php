<?php
require_once __DIR__ . '/includes/functions.php';
exigir_login();

$pdo = db();
$erros = [];
$d = ['medico_id' => 0, 'data_hora' => '', 'motivo' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$erros, $dados] = validar_consulta($_POST, null, true);
    $d = ['medico_id' => $dados['medico_id'], 'data_hora' => (string)($_POST['data_hora'] ?? ''), 'motivo' => $dados['motivo']];
    if (!$erros) {
        $st = $pdo->prepare('INSERT INTO consultas (usuario_id, medico_id, data_hora, motivo) VALUES (?, ?, ?, ?)');
        $st->execute([usuario_atual()['id'], $dados['medico_id'], $dados['data_hora'], $dados['motivo']]);
        flash('ok', 'Consulta agendada com sucesso!');
        redirect('minhas-consultas.php');
    }
}

$medicos = $pdo->query(
    'SELECT m.id, m.nome, e.nome AS esp FROM medicos m
     JOIN especialidades e ON e.id = m.especialidade_id WHERE m.ativo = 1 ORDER BY e.nome, m.nome'
)->fetchAll();

$titulo = 'Agendar consulta';
require __DIR__ . '/includes/header.php';
?>
<div class="form-box">
  <h1>Agendar consulta</h1>
  <?php erros_show($erros); ?>
  <form method="post" novalidate>
    <?= csrf_field() ?>
    <label for="medico_id">Médico</label>
    <select id="medico_id" name="medico_id" required>
      <option value="">Selecione...</option>
      <?php foreach ($medicos as $m): ?>
        <option value="<?= (int)$m['id'] ?>" <?= $d['medico_id'] === (int)$m['id'] ? 'selected' : '' ?>>
          <?= e($m['nome']) ?> &mdash; <?= e($m['esp']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="data_hora">Data e hora (07:00 às 18:00)</label>
    <input id="data_hora" name="data_hora" type="datetime-local" step="1800" data-min-agora required value="<?= e($d['data_hora']) ?>">

    <label for="motivo">Motivo da consulta</label>
    <textarea id="motivo" name="motivo" rows="3" maxlength="255" required><?= e($d['motivo']) ?></textarea>

    <button type="submit" class="btn">Confirmar agendamento</button>
  </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

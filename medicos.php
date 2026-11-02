<?php
$titulo = 'Médicos';
require_once __DIR__ . '/includes/header.php';
$pdo = db();
$esp = filter_input(INPUT_GET, 'esp', FILTER_VALIDATE_INT) ?: 0;
$espList = $pdo->query('SELECT id, nome FROM especialidades ORDER BY nome')->fetchAll();

$sql = 'SELECT m.nome, m.crm, e.nome AS especialidade
        FROM medicos m JOIN especialidades e ON e.id = m.especialidade_id
        WHERE m.ativo = 1' . ($esp ? ' AND m.especialidade_id = ?' : '') . ' ORDER BY m.nome';
$st = $pdo->prepare($sql);
$st->execute($esp ? [$esp] : []);
$medicos = $st->fetchAll();
?>
<div class="barra">
  <h1>Corpo clínico</h1>
  <form method="get">
    <label for="esp" class="pequeno">Filtrar por especialidade</label>
    <select name="esp" id="esp" onchange="this.form.submit()">
      <option value="0">Todas</option>
      <?php foreach ($espList as $o): ?>
        <option value="<?= (int)$o['id'] ?>" <?= $esp === (int)$o['id'] ? 'selected' : '' ?>><?= e($o['nome']) ?></option>
      <?php endforeach; ?>
    </select>
    <noscript><button class="btn btn-sm" type="submit">Filtrar</button></noscript>
  </form>
</div>

<?php if (!$medicos): ?>
  <p>Nenhum médico encontrado.</p>
<?php else: ?>
  <div class="grade">
    <?php foreach ($medicos as $m): ?>
      <article class="card">
        <h3><?= e($m['nome']) ?></h3>
        <p><span class="selo"><?= e($m['especialidade']) ?></span></p>
        <p class="pequeno"><?= e($m['crm']) ?></p>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>

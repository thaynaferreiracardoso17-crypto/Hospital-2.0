<?php
$titulo = 'Especialidades';
require_once __DIR__ . '/includes/header.php';
$lista = db()->query(
    'SELECT e.id, e.nome, e.descricao, COUNT(m.id) AS total
     FROM especialidades e
     LEFT JOIN medicos m ON m.especialidade_id = e.id AND m.ativo = 1
     GROUP BY e.id ORDER BY e.nome'
)->fetchAll();
?>
<h1>Especialidades</h1>
<div class="grade">
  <?php foreach ($lista as $es): ?>
    <article class="card">
      <h3><?= e($es['nome']) ?></h3>
      <p><?= e($es['descricao']) ?></p>
      <p><span class="selo"><?= (int)$es['total'] ?> médico(s)</span></p>
      <a href="medicos.php?esp=<?= (int)$es['id'] ?>">Ver médicos</a>
    </article>
  <?php endforeach; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

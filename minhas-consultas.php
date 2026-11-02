<?php
require_once __DIR__ . '/includes/functions.php';
exigir_login();

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    // só cancela consulta do próprio usuário
    $st = $pdo->prepare("UPDATE consultas SET status = 'cancelada' WHERE id = ? AND usuario_id = ? AND status = 'agendada'");
    $st->execute([$id, usuario_atual()['id']]);
    flash($st->rowCount() ? 'ok' : 'erro', $st->rowCount() ? 'Consulta cancelada.' : 'Não foi possível cancelar.');
    redirect('minhas-consultas.php');
}

$st = $pdo->prepare(
    'SELECT c.id, c.data_hora, c.motivo, c.status, m.nome AS medico, e.nome AS esp
     FROM consultas c
     JOIN medicos m ON m.id = c.medico_id
     JOIN especialidades e ON e.id = m.especialidade_id
     WHERE c.usuario_id = ? ORDER BY c.data_hora DESC'
);
$st->execute([usuario_atual()['id']]);
$lista = $st->fetchAll();

$titulo = 'Minhas consultas';
require __DIR__ . '/includes/header.php';
?>
<div class="barra">
  <h1>Minhas consultas</h1>
  <a class="btn" href="agendar.php">Nova consulta</a>
</div>
<?php if (!$lista): ?>
  <p>Você ainda não tem consultas.</p>
<?php else: ?>
  <div class="tabela-wrap">
    <table>
      <thead><tr><th>Data</th><th>Médico</th><th>Motivo</th><th>Status</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($lista as $c): ?>
        <tr>
          <td><?= e(date('d/m/Y H:i', strtotime($c['data_hora']))) ?></td>
          <td><?= e($c['medico']) ?><br><span class="pequeno"><?= e($c['esp']) ?></span></td>
          <td><?= e($c['motivo']) ?></td>
          <td class="status-<?= e($c['status']) ?>"><?= e(ucfirst($c['status'])) ?></td>
          <td>
            <?php if ($c['status'] === 'agendada'): ?>
              <form method="post" data-confirmar="Cancelar esta consulta?">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <button class="btn btn-perigo btn-sm" type="submit">Cancelar</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>

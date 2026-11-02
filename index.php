<?php
$titulo = 'Início';
require_once __DIR__ . '/includes/header.php';
$esps = db()->query('SELECT nome, descricao FROM especialidades ORDER BY nome LIMIT 6')->fetchAll();
?>
<section class="hero">
  <h1>Cuidar de você é a nossa prioridade</h1>
  <p>Consultas, exames e atendimento humanizado com equipe médica experiente. Agende online em poucos minutos.</p>
  <div class="hero-acoes">
    <a class="btn" href="agendar.php">Agendar consulta</a>
    <a class="btn btn-borda" href="medicos.php">Conheça os médicos</a>
  </div>
</section>

<section>
  <h2>Nossas especialidades</h2>
  <div class="grade">
    <?php foreach ($esps as $es): ?>
      <article class="card">
        <h3><?= e($es['nome']) ?></h3>
        <p><?= e($es['descricao']) ?></p>
      </article>
    <?php endforeach; ?>
  </div>
  <p><a href="especialidades.php">Ver todas as especialidades &rarr;</a></p>
</section>

<section class="grade">
  <div class="card"><h3>Agendamento online</h3><p>Escolha médico, dia e horário sem sair de casa.</p></div>
  <div class="card"><h3>Atendimento 24h</h3><p>Pronto-socorro aberto todos os dias, a qualquer hora.</p></div>
  <div class="card"><h3>Seus dados protegidos</h3><p>Pedimos só o necessário e guardamos com segurança.</p></div>
</section>

<div class="emergencia">
  <strong>Emergência?</strong> Ligue <strong>192 (SAMU)</strong> ou venha direto ao nosso pronto-socorro.
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

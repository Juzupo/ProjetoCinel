<?php
  //logout.php
  session_start();
  include 'cabecalho.php';

  $mensagem = "Página não disponível a visitantes.";

  if(isset($_SESSION['user']))
  $mensagem = 'Até a próxima, '.$_SESSION['user'].'!';

unset($_SESSION['user']);

echo ' <div class="login_sucesso">
        '.$mensagem.'<br><br>
        <a href="index.php">Início<a/>
        </div>
';

  include 'rodape.php';
?>
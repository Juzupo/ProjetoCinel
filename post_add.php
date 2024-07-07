<?php

//post_add.php
session_start();
if(!isset($_SESSION['user']))
{
  include 'cabecalho.php';
  echo '
      <div class="erro">
      Não tem permissão para ver o conteúdo desta página.<br><br>
      <a href="index.php">Retroceder</a>
      </div>  
  ';
  include 'rodape.php';
  exit;
}

include 'cabecalho.php';

//Dados do utilizador que está ligado
echo '
  <div class="dados_utilizador">
    <img src="image/'.$_SESSION['avatar'].'"><span>'.$_SESSION['user'].'</span> | <a href="logout.php">Logout</a>
  </div>
';

//ir buscar dados do formulario
$id_user = $_POST['id_user'];
$id_post = $_POST['id_post'];
$titulo = $_POST['text_titulo'];
$mensagem = $_POST['text_mensagem'];
$editar = false;

//verificar se os campos estão preenchidos
if($titulo == "" || $mensagem == "")
{
  echo '
    <div class="erro">
      Não foram preenchidos os campos necessários.<br><br>
      <a href="editor_post.php">Tente novamente</a>
    </div>  
  ';
  include 'rodape.php';
  exit;
}

include 'config.php';
$ligacao = new PDO("mysql:dbname=$base_dados;host=$host", $user, $password);

if($id_post == -1)
{
  //vai buscar o id_pos disponivel
  $motor = $ligacao->prepare("SELECT MAX(id_post) AS MaxID FROM posts");
  $motor->execute();

  $id_post = $motor->fetch(PDO::FETCH_ASSOC)['MaxID'];

  if($id_post == null)
      $id_post = 1;

    else
      $id_post++;

    $editar = false;
}
else
{
  $editar = true;
}

if(!$editar)
{
  //data atual
  $data = date('Y-m-d h:i:s', time());

  $motor = $ligacao->prepare("INSERT INTO posts VALUES(?,?,?,?,?)");
  $motor->bindParam(1, $id_post, PDO::PARAM_INT);
  $motor->bindParam(2, $id_user, PDO::PARAM_INT);
  $motor->bindParam(3, $titulo, PDO::PARAM_STR);
  $motor->bindParam(4, $mensagem, PDO::PARAM_STR);
  $motor->bindParam(5, $data, PDO::PARAM_STR);
  $motor->execute();
}
else
{
  $data = date('Y-m-d h:i:s', time());

  $motor = $ligacao->prepare("UPDATE posts SET titulo = :tit, mensagem = :mess, data_post = :dat WHERE id_post = :pid");

  $motor->bindParam(":pid", $id_post, PDO::PARAM_INT);
  $motor->bindParam(":tit", $titulo, PDO::PARAM_STR);
  $motor->bindParam(":mess", $mensagem, PDO::PARAM_STR);
  $motor->bindParam(":dat", $data, PDO::PARAM_STR);
  $motor->execute();

}

echo '
  <div class="login_sucesso">
    Post gravado com sucesso.<br><br>
    <a href="forum.php">Voltar<a/>
  </div>
';

include 'rodape.php';

?>
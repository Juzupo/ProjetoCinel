<?php

//forum.php
session_start();
if(!isset($_SESSION['user']))
{
  include 'cabecalho.php';
  echo '<div class="erro">
        Não tem permissão para ver o conteúdo do forum.<br><br>
        <a href="index.php">Retroceder</a>
        </div>
  ';
  include 'rodape.php';
  exit;
}

include 'cabecalho.php';

//dados do utilizador que está ligado
echo '
    <div class="dados_utilizador">
    <img src="image/'.$_SESSION['avatar'].'">
    <span>'.$_SESSION['user'].'</span> | <a href="logout.php">Logout</a>
    </div>
';

echo '
    <div class="novo_post">
    <a href="editor_post.php">Novo Post</a>
    </div>
';

include 'config.php';

$ligacao = new PDO("mysql:dbname=$base_dados;host=$host", $user, $password);

//ir buscar os dados dos posts
$sql = "SELECT * FROM posts INNER JOIN users ON posts.id_user = users.id_user ORDER BY data_post DESC";
    $motor = $ligacao->prepare($sql);
    $motor->execute();
    $ligacao = null;

    if($motor->rowCount() == 0)
    {
        echo '<div class="login_sucesso">
                Não existem posts no forum.
              </div>
        ';
    }
    else
    {
      foreach($motor as $post)
      {
          //dados do post
          $id_post = $post['id_post'];
          $id_user = $post['id_user'];
          $titulo = $post['titulo'];
          $mensagem = $post['mensagem'];
          $data_post = $post['data_post'];

          //dados do utilizador
          $username = $post['username'];
          $avatar = $post['avatar'];

          echo' <div class="post">';

          //dados do user
          echo '<img src="image/'.$avatar.'">';
          echo '<span id="post_username">'.$username.'</span>';
          echo '<span id="post_titulo">'.$titulo.'</span>';
          echo '<hr>';
          echo '<div id="post_mensagem">'.$mensagem.'</div>';

          //data e hora da mensagem do post
          echo '<div id="post_data">';

          //adicionar link editar para o utilizador ativo
          if($id_user == $_SESSION['id_user'])
          {
            echo '<a href="editor_post.php?pid='.$id_post.'" id="editar">Editar</a>';
          }

          echo $data_post;
          echo '<span id="id_post">#'.$id_post.'</span>';
          echo '</div></div>';
      }
    }


    //Ir buscar users
    $ligacao = new PDO("mysql:dbname=$base_dados;host=$host", $user, $password);
    $motor = $ligacao->prepare("SELECT id_user FROM users");
    $motor->execute();
    $numUsers = $motor->rowCount();
    if($numUsers == null)
        $numUsers = 0;

        $motor = $ligacao->prepare("SELECT id_post FROM posts");
        $motor->execute();
        $numPosts = $motor->rowCount();
        if($numPosts == null)
          $numPosts = 0;

          $ligacao = null;

          echo '<div>
                Número de utilizadores registados: <strong>'.$numUsers.'</strong> | Número total de posts: <strong>'.$numPosts.'</strong>
                </div>
          ';
          include 'rodape.php';
?>

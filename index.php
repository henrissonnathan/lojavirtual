<?php
//abrir e utilizar uma sessao
session_start();
//incluir a conexao com banco de dados
require "configs/conexao.php";

date_default_timezone_set('America/Sao_Paulo');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>loja virtual</title> 
    <base href="<?= $base ?>">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <li class="nav-item">
            <a class="nav-link" href="home">Todos</a>
          </li>
          <?php
          $sqlCategorias = "select * from categorias order by categoria";
          $consultaCategorias = $pdo->prepare($sqlCategorias);
          $consultaCategorias->execute();

          while ($dados = $consultaCategorias->fetch(PDO::FETCH_OBJ)) {
          ?>
            <li class="nav-item">
              <a class="nav-link" href="home/<?= $dados->id ?>"><?= $dados->categoria ?></a>
            </li>

          <?php
          }

          ?>

        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

  <main class="container">
    <?php
    //print_r( $_GET)
    $pagina = "home";

    if (isset($_GET["param"])) {
      $param = trim($_GET["param"]);
      $param = explode("/", $param);

      //print_r( $param);3
      $pagina = $param[0];
    }

    //home -> paginas/home.php
    $pagina = "paginas/{$pagina}.php";

    //echo $pagina;
    if (file_exists($pagina)) {
      include $pagina;
    } else {
      include "paginas/erro.php";
    }
    ?>

  </main>

  <footer class="bg-light">
    <p class="text-center">
      desenvolvido por Henrisson
    </p>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>




</body>

</html>
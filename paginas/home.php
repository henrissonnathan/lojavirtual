<div class="row">
    <?php
    $categoria = $param[1] ?? NULL;
    
    if (!$categoria) {
        $sqlProduto = "SELECT * from produtos order by produto";
        $consultaProduto = $pdo->prepare($sqlProduto);
        $consultaProduto->execute();
    } else {
        $sqlProduto = "SELECT * FROM produtos WHERE categorias_id = :categorias_id ORDER BY produto";
        $consultaProduto = $pdo->prepare($sqlProduto);
        $consultaProduto->bindValue(':categorias_id', $categoria);
        $consultaProduto->execute();
    }
    while ($dados = $consultaProduto->fetch(PDO::FETCH_OBJ)) {
        $valor = str_replace(".", ",", ($dados->valor));
        $valor = floatval($valor);
        $id = $dados->id




    ?>
        <div class="col-12 col-md-3">
            <div class="card">
                <img src="http://localhost/admin/fotos/<?=$dados->imagem?>p.jpg" alt="<?= $dados->produto ?>" class="w-100">
                <div class="card-body text-center">
                    <p class="titulo"><strong><?= $dados->produto ?></strong></p>
                    <p>
                    <p>$<?= $valor ?></p>
                    <a href="produto/<?= $dados->id ?>" class="btn btn-warning">
                        ver detalhes
                    </a>
                    </p>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>
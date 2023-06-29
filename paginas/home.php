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
        <div class="col-12 col-md-3 ">
            <div class="card rounded-3 p-3 border border-info">
                <img src="http://localhost/admin/fotos/<?=$dados->imagem?>p.jpg" alt="<?= $dados->produto ?>" class="w-10">
                <div class="card-body text-center ">
                    <p class="titulo text-primary"><strong><?= $dados->produto ?></strong></p>
                    <p>
                    <p class="text-warning">$<?= $valor ?></p>
                    <a href="produto/<?= $dados->id ?>" class="btn btn-primary rounded">
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
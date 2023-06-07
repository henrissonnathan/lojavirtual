<?php
;
;



    $sql = "select * from produtos where id = :id limit 1";
    $consulta = $pdo->prepare($sql);
    $consulta->bindParam(":id", $id);
    $consulta->execute();
    $dado = $consulta->fetch(PDO::FETCH_OBJ);

?>
<html>
<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>Produtos</title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
</head>

<body>
    <img src="fotos/12.png" class="img-div">
    <div class="card-1">
    <div class="row">
        <div class="col-md-3">
            <img src="http://localhost/admin/fotos/<?=$dado->imagem?>p.jpg" id="foto1" />
        </div>
        <div class="col-md-9">
            <div class="card-grande-produto">
                <div class="row">
                    <div class="col-md-6">
                    <p><b>Nome:</b> <?=$dado->produto?></p>
                    <p><b>Valor:</b> R$ <span id="valorTotal"><?=$dado->valor?></span></p>
                    <p><b>Categoria:</b>
                        <?php
                        $sqlCategoria = "select * from categorias where id = :categorias_id";
                        $consultaCategoria = $pdo->prepare($sqlCategoria);
                        $consultaCategoria->bindParam(":categorias_id", $dado->categorias_id);
                        $consultaCategoria->execute();
                        $dadosCategoria = $consultaCategoria->fetch(PDO::FETCH_OBJ);
                        ?>
                        <?=$dadosCategoria->categoria?>
                    </p>
                    <p><b>Descrição:</b> <?=$dado->descricao?></p>
                    </div>
                    <div class="col-md-6 parcelas_container">
                        <div class="text-center">
                            <h1>Parcelamento</h1>
                            <label for="numParcelas">Número de Parcelas:</label>
                            <br>
                            <input type="text" id="numParcelas" min="1" max="6" class="input_dados" />
                            <button onclick="calcularParcelas()" class="botao">Calcular</button>
                            <br>
                            <p id="parcelas"></p> 
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="cep text-center">
        <form>
            <div class="row">
                <div>
                    <p class="txt">Informe CEP de origem</p>
                    <input type="text" id="cep-origem" class="input_dados_cep">
                </div>
                <div>
                    <p class="txt">Informe CEP destino</p>
                    <input type="text" id="cep-destino" class="input_dados_cep">
                </div>
            </div>
            <input type="button" onclick="calcularFrete()" value="Calcular" class="botao">
            <div class="resultado"></div>
        </form>
    </div>

    <?php
        require "footer.php";
    ?>

    <script>
        async function calcularFrete() {
            const cepOrigem = String(document.querySelector('#cep-origem').value);
            const cepDestino = String(document.querySelector('#cep-destino').value);
            const apareceTela = document.querySelector('.resultado');
            const endpoint = `https://www.cepcerto.com/ws/json-frete/${cepOrigem}/${cepDestino}/2000`;
            const resposta = await fetch(endpoint);
            const dados = await resposta.json();
            apareceTela.innerHTML = `O valor do frete é: R$ ${dados.valorpac}`;
        }


        
        function calcularParcelas() {
            const valorTotal = parseFloat(document.getElementById('valorTotal').innerText);
            const numParcelas = parseInt(document.getElementById('numParcelas').value);

            if (!isNaN(valorTotal) && !isNaN(numParcelas) && numParcelas > 0 && !isNaN(numParcelas) && numParcelas <= 6) {
                const valorParcela = valorTotal / numParcelas;
                document.getElementById('parcelas').innerText = 'Valor das parcela: R$ ' + valorParcela.toFixed(2);
            } else if(numParcelas > 6) {
                document.getElementById('parcelas').innerText = 'A quantidade máxima de parcelas é 6!';
            }else if(numParcelas <=0) {
                document.getElementById('parcelas').innerText = 'A quantidade minima de parcelas é 1!';
            }
        }

        

    </script>
</body>
</html>
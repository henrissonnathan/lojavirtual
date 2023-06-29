<?php
$id = $param[1] ?? NULL;

if (!$id) {
?>
    <p class="alert alert-danger text-center">
        Ops! produto inválido
    </p>
<?php
} else {
    $sql = "SELECT * FROM produtos WHERE id = :id";
    $consulta = $pdo->prepare($sql);
    $consulta->bindValue(':id', $id);
    $consulta->execute();
    $dados = $consulta->fetch(PDO::FETCH_OBJ);
    $valor = str_replace(".", ",", ($dados->valor));
    $valor = floatval($valor);
    $categorias = $dados->categorias_id;
    $valorTotal = $valor; // Variável para armazenar o valor total inicial
}
?>

<div class="row card rounded-4 p-4 border border-info">
    <div class="row">
        <div class="col-12 col-md-3">
            <img src="http://localhost/admin/fotos/<?= $dados->imagem ?>p.jpg" alt="<?= $dados->produto ?>" class="w-200">
        </div>
        <div class="col-12 col-md-6">
            <h1 class="text-center text-primary"><?= $dados->produto ?></h1>
            <p class="text-warning">R$<span id="valorTotal" ><?= $valor ?></span></p>
            <p class="text-primary"><?=$dados->descricao?></p>
        </div>
        <div class="col-12 col-md-3 text-primary">
            <h4><label for="numParcelas">Valor de parcela:</label></h4>
            <h3 id="parcelas" class="text-warning"></h3>
            <br>
            <h5>Parcelas (máx 6):</h5>
            <input type="number" id="numParcelas" min="1" max="6" class="input_dados col-md-2 form-control rounded text-primary" onchange="atualizarValorTotal()" />
            <button onclick="calcularParcelas()" class="btn btn-primary rounded">Calcular</button>
            <br>
        </div>
    </div>

    <div class="cep text-center">
        <form>
            <div class="row">
                <div class="col-12 col-md-4"></div>
                <div class="col-12 col-md-2">
                    <p class="txt text-primary">Informe o CEP de origem:</p>
                    <input type="text" id="cep-origem" class="input_dados_cep form-control rounded">
                </div>
                <div class="col-12 col-md-2">
                    <p class="txt text-primary">Informe o CEP de destino:</p>
                    <input type="text" id="cep-destino" class="input_dados_cep form-control rounded">
                </div>
                <div class="col-12 col-md-5"></div>
                <input type="button" onclick="calcularFrete()" value="Calcular" class="btn btn-primary rounded col-12 col-md-2 m-2">
                <div class="col-12 col-md-5"></div>

                <div>
                    <h5 class="resultado"></h5>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <?php
    $sqlProduto = "SELECT * FROM produtos WHERE categorias_id = :categorias_id ORDER BY produto";
    $consultaProduto = $pdo->prepare($sqlProduto);
    $consultaProduto->bindValue(':categorias_id', $categorias);
    $consultaProduto->execute();
    while ($dados = $consultaProduto->fetch(PDO::FETCH_OBJ)) {
        $valor = str_replace(".", ",", ($dados->valor));
        $valor = floatval($valor);
        $id = $dados->id;

        // Verificar se o ID do produto é diferente do ID do produto atual
        if ($id != $param[1]) {
    ?>
            <div class="col-12 col-md-3">
                <div class="card rounded-3 p-3 border border-info">
                    <img src="http://localhost/admin/fotos/<?= $dados->imagem ?>p.jpg" alt="<?= $dados->produto ?>" class="w-100">
                    <div class="card-body text-center">
                        <p class="titulo text-primary"><strong><?= $dados->produto ?></strong></p>
                        <p class="text-primary text-warning">R$<?= $valor ?></p>
                        <a href="produto/<?= $dados->id ?>" class="btn btn-primary rounded">
                            Ver detalhes
                        </a>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>

<script>
    async function calcularFrete() {
        const cepOrigem = String(document.querySelector('#cep-origem').value);
        const cepDestino = String(document.querySelector('#cep-destino').value);
        const apareceTela = document.querySelector('.resultado');
        const endpoint = `https://www.cepcerto.com/ws/json-frete/${cepOrigem}/${cepDestino}/2000`;
        const resposta = await fetch(endpoint);
        const dados = await resposta.json();
        apareceTela.innerHTML = `O valor do frete é: R$ <span class="text-warning">${dados.valorpac}</span>`;
    }

    function atualizarValorTotal() {
        const valor = parseFloat(document.getElementById('valorTotal').innerText);
        let numParcelas = parseInt(document.getElementById('numParcelas').value);

        // Limitar o número de parcelas a no máximo 6
        if (!isNaN(numParcelas)) {
            numParcelas = Math.min(Math.max(numParcelas, 1), 6);
            document.getElementById('numParcelas').value = numParcelas;
        }

        if (!isNaN(valor) && !isNaN(numParcelas) && numParcelas > 0 && numParcelas <= 6) {
            const valorParcela = valor / numParcelas;
            document.getElementById('parcelas').innerText = 'R$ ' + valorParcela.toFixed(2);
            document.getElementById('valorTotal').innerText = valor.toFixed(2);
        } else if (numParcelas > 6) {
            document.getElementById('parcelas').innerText = 'A quantidade máxima de parcelas é 6!';
        } else if (numParcelas <= 0) {
            document.getElementById('parcelas').innerText = 'A quantidade mínima de parcelas é 1!';
        }
    }

    function calcularParcelas() {
        let numParcelas = parseInt(document.getElementById('numParcelas').value);

        // Limitar o número de parcelas a no máximo 6
        if (!isNaN(numParcelas)) {
            numParcelas = Math.min(Math.max(numParcelas, 1), 6);
            document.getElementById('numParcelas').value = numParcelas;
        }

        const valorTotal = parseFloat(document.getElementById('valorTotal').innerText);

        if (!isNaN(valorTotal) && !isNaN(numParcelas) && numParcelas > 0 && numParcelas <= 6) {
            const valorParcela = valorTotal / numParcelas;
            document.getElementById('parcelas').innerText = 'R$ ' + valorParcela.toFixed(2);
        } else if (numParcelas > 6) {
            document.getElementById('parcelas').innerText = 'A quantidade máxima de parcelas é 6!';
        } else if (numParcelas <= 0) {
            document.getElementById('parcelas').innerText = 'A quantidade mínima de parcelas é 1!';
        }
    }
</script>

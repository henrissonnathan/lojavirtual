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

    $valorTotal = $valor; // Variável para armazenar o valor total inicial
}
?>

<div class="row card">
    <div class="row">
        <div class="col-12 col-md-3">
            <img src="http://localhost/admin/fotos/<?= $dados->imagem ?>p.jpg" alt="<?= $dados->produto ?>" class="w-200">
        </div>
        <div class="col-12 col-md-6">
            <h1 class="text-center"><?= $dados->produto ?></h1>
            <p>R$<span id="valorTotal"><?= $valor ?></span></p>
            <p><?= $dados->descricao ?></p>
        </div>
        <div class="col-6 col-md-3">
            
            <h4><label for="numParcelas">Valor de cada parcelas:</label><h4>
            <h3 id="parcelas"></h3>
            <br>
            <h5>Numero de parcelas(max 6)</h5>
            <input type="number" id="numParcelas" min="1" max="6" class="input_dados" onchange="atualizarValorTotal()" />
            <button onclick="calcularParcelas()" class="botao">Calcular</button>
            <br>

        </div>
    </div>
    <div class="cep text-center">
        <form>
            <div class="row">
                <div class="col-12 col-md-6">
                    <p class="txt">Informe CEP de origem</p>
                    <input type="text" id="cep-origem" class="input_dados_cep">
                </div>
                <div class="col-12 col-md-6">
                    <p class="txt">Informe CEP destino</p>
                    <input type="text" id="cep-destino" class="input_dados_cep">
                </div>
                <div class="col-12 col-md-5"></div>
                <input type="button" onclick="calcularFrete()" value="Calcular" class="botao col-12 col-md-2">
                <div class="col-12 col-md-5"></div>
                <div>
                    <h5 class="resultado"></h5>
                </div>
        </form>
    </div>
</div>


<?php

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

    function atualizarValorTotal() {
        const valor = parseFloat(document.getElementById('valorTotal').innerText);
        const numParcelas = parseInt(document.getElementById('numParcelas').value);

        if (!isNaN(valor) && !isNaN(numParcelas) && numParcelas > 0 && numParcelas <= 6) {
            const valorParcela = valor / numParcelas;
            document.getElementById('parcelas').innerText = 'R$ ' + valorParcela.toFixed(2) ;
            document.getElementById('valorTotal').innerText = valor.toFixed(2);
        } else if (numParcelas > 6) {
            document.getElementById('parcelas').innerText = 'A quantidade máxima de parcelas é 6!';
        } else if (numParcelas <= 0) {
            document.getElementById('parcelas').innerText = 'A quantidade mínima de parcelas é 1!';
        }
    }

    function calcularParcelas() {
        const valorTotal = parseFloat(document.getElementById('valorTotal').innerText);
        const numParcelas = parseInt(document.getElementById('numParcelas').value);

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
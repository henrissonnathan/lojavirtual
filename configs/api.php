<?php


 $cep_destino = $param[1] ?? NULL; 
    $peso          = $param[2] ?? NULL;
    $valor         = $param[3] ?? NULL;
    $tipo_do_frete = $param[4] ?? NULL;
    $altura        = $param[5] ?? NULL;
    $largura       = $param[6] ?? NULL;
    $comprimento   = $param[7] ?? NULL;
if ( (!empty($cep_destino)) AND (!empty($peso))  AND (!empty($valor))  AND (!empty($tipo_do_frete)) AND (!empty($altura)) AND (!empty($largura)) AND (!empty($comprimento)) AND ($opcao == "calcula-frete")) {
        $cep_origem = "87390000";    
    
        $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
        $url .= "nCdEmpresa=";
        $url .= "&sDsSenha=";
        $url .= "&sCepOrigem=" . $cep_origem;
        $url .= "&sCepDestino=" . $cep_destino;
        $url .= "&nVlPeso=" . $peso;
        $url .= "&nVlLargura=" . $largura;
        $url .= "&nVlAltura=" . $altura;
        $url .= "&nCdFormato=1";
        $url .= "&nVlComprimento=" . $comprimento;
        $url .= "&sCdMaoProria=n";
        $url .= "&nVlValorDeclarado=" . $valor;
        $url .= "&sCdAvisoRecebimento=n";
        $url .= "&nCdServico=" . $tipo_do_frete;
        $url .= "&nVlDiametro=0";
        $url .= "&StrRetorno=xml";
    
        $xmlString = file_get_contents($url);
        $resposta = simplexml_load_string($xmlString);
    }

    echo json_encode($resposta);
    ?>
    <form name="frete" method="post" action="" data-parsley-validate="" targer="_self">
    <input type="text" id="cep" maxlength="9" placeholder="Digite o CEP">

<?php
     $cep_destino = "valordoseuinput"; 
     $peso          = $param[2] ?? NULL;
     $valor         = $param[3] ?? NULL;
     $tipo_do_frete = $param[4] ?? NULL;
     $altura        = $param[5] ?? NULL;
     $largura       = $param[6] ?? NULL;
     $comprimento   = $param[7] ?? NULL;
?>
<?php


// ---------------------------- Fornecedores Todos
$app->get('/Fornecedores/De/{dataInicio}/Ate/{dataFim}/Status/{status}', function ($dataInicio, $dataFim, $status) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getTodosFornecedores($dataInicio, $dataFim, $status);
});

//Status
$app->get('/Importer/CurvaAbc/ComprasFornecedores/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\ComprasFornecedores\Controller\FornecedoresController($app);
    return $class->importCurvaAbc($mes, $ano, $int);
});

$app->get('/Importer/CurvaAbc/ComprasFornecedores/Ano/{ano}/Intervalo/{int}', function ($ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\ComprasFornecedores\Controller\FornecedoresController($app);
    for($x=1;$x<=12;$x++){
       $class->importCurvaAbc($x, $ano, $int);
    }
});

// ----------------------------- Fornecedores Status
// ---------------------------- Fornecedors Status
$app->get('/Fornecedores/Positivos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getFornecedoresPositivos($mes, $ano, $int);
});

$app->get('/Fornecedores/Regulares/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getFornecedoresRegulares($mes, $ano, $int);
});


$app->get('/Fornecedores/Novos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getFornecedoresNovos($mes, $ano, $int);
});

$app->get('/Fornecedores/Negativos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getFornecedoresNegativos($mes, $ano, $int);
});

$app->get('/Fornecedores/Recuperados/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getFornecedoresRecuperados($mes, $ano, $int);
});

// ---------------------------- Fornecedores Status Comparativo
$app->get('/Fornecedores/Comparativo/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getComparativoFornecedores($mes, $ano, $int);
});

$app->get('/Fornecedores/ComparativoHead/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getComparativoHeadFornecedores($mes, $ano, $int);
});


// Perfil do Fornecedor
$app->get('/Fornecedores/Perfil/{idFornecedor}/{qtdValores}/Mensal/Ano/{ano}', function ($idFornecedor, $qtdValores, $ano) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getVendasMensalFor($idFornecedor, $ano, $qtdValores);
});

$app->get('/Fornecedores/Perfil/{idFornecedor}/Produtos', function ($idFornecedor) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getProdutosForFornecedor($idFornecedor);
});

$app->get('/Fornecedores/Perfil/{idFornecedor}/Produtos/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idFornecedor, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getProdutosForFornecedor($idFornecedor, $ano, $mes, $intervalo);
});

$app->get('/Fornecedores/Perfil/{idFornecedor}/Resumo', function ($idFornecedor) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getResumo($idFornecedor);
});

$app->get('/Fornecedores/Perfil/{idFornecedor}/Resumo/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idFornecedor, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getResumo($idFornecedor, $ano, $mes, $intervalo);
});

$app->get('/Fornecedores/Perfil/{idFornecedor}/Ano/{ano}/HistoricoValoresCompra', function ($idFornecedor, $ano) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getHistoricoValoresCompra($idFornecedor, $ano);
});

$app->get('/Fornecedores/Perfil/{idFornecedor}/Ano/{ano}/HistoricoValoresVenda', function ($idFornecedor, $ano) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getHistoricoValoresVenda($idFornecedor, $ano);
});

$app->get('/Fornecedores/Perfil/{idFornecedor}/Ano/{ano}/HistoricoValoresMargem', function ($idFornecedor, $ano) use ($app) {
    $class = new Auper\Fornecedores\Controller\FornecedoresController($app);
    return $class->getHistoricoValoresMargem($idFornecedor, $ano);
});

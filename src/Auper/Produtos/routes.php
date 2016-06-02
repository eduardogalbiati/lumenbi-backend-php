<?php


// ---------------------------- Produtos Todos
$app->get('/Produtos/De/{dataInicio}/Ate/{dataFim}/Status/{status}', function ($dataInicio, $dataFim, $status) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getTodosProdutos($dataInicio, $dataFim, $status);
});

// ---------------------------- Produtos Status
$app->get('/Produtos/Positivos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getProdutosPositivos($mes, $ano, $int);
});

$app->get('/Produtos/Novos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getProdutosNovos($mes, $ano, $int);
});

$app->get('/Produtos/Negativos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getProdutosNegativos($mes, $ano, $int);
});

$app->get('/Produtos/Recuperados/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getProdutosRecuperados($mes, $ano, $int);
});

// ---------------------------- Produtos Status Comparativo
$app->get('/Produtos/Comparativo/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getComparativoProdutos($mes, $ano, $int);
});

$app->get('/Produtos/ComparativoHead/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getComparativoHeadProdutos($mes, $ano, $int);
});
// -------------------------- Perfil do Produto
$app->get('/Produtos/Perfil/{idProduto}/{qtdValores}/Mensal/Ano/{ano}', function ($idProduto, $qtdValores, $ano) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getVendasMensalFor($idProduto, $ano, $qtdValores);
});

$app->get('/Produtos/Perfil/{idProduto}/Clientes', function ($idProduto) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getClientesForProduto($idProduto);
});

$app->get('/Produtos/Perfil/{idProduto}/Clientes/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idProduto, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getClientesForProduto($idProduto, $ano, $mes, $intervalo);
});

$app->get('/Produtos/Perfil/{idProduto}/Fornecedores', function ($idProduto) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getFornecedoresForProduto($idProduto);
});

$app->get('/Produtos/Perfil/{idProduto}/Fornecedores/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idProduto, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getFornecedoresForProduto($idProduto, $ano, $mes, $intervalo);
});

$app->get('/Produtos/Perfil/{idProduto}/Vendedores', function ($idProduto) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getVendedoresForProduto($idProduto);
});

$app->get('/Produtos/Perfil/{idProduto}/Vendedores/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idProduto, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getVendedoresForProduto($idProduto, $ano, $mes, $intervalo);
});

$app->get('/Produtos/Perfil/{idProduto}/Resumo', function ($idProduto) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getResumo($idProduto);
});

$app->get('/Produtos/Perfil/{idProduto}/Resumo/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idProduto, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getResumo($idProduto, $ano, $mes, $intervalo);
});

$app->get('/Produtos/Perfil/{idProduto}/Ano/{ano}/HistoricoValoresCompra', function ($idProduto, $ano) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getHistoricoValoresCompra($idProduto, $ano);
});

$app->get('/Produtos/Perfil/{idProduto}/Ano/{ano}/HistoricoValoresVenda', function ($idProduto, $ano) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getHistoricoValoresVenda($idProduto, $ano);
});

$app->get('/Produtos/Perfil/{idProduto}/Ano/{ano}/HistoricoValoresMargem', function ($idProduto, $ano) use ($app) {
    $class = new Auper\Produtos\Controller\ProdutosController($app);
    return $class->getHistoricoValoresMargem($idProduto, $ano);
});
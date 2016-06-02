<?php

// ---------------------------- Vendedores Todos
$app->get('/Vendedores/De/{dataInicio}/Ate/{dataFim}/Status/{status}', function ($dataInicio, $dataFim, $status) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->getTodosVendedores($dataInicio, $dataFim, $status);
});


// Perfil do Vendedor
$app->get('/Vendedores/Perfil/{idVendedor}/{qtdValores}/Mensal/Ano/{ano}', function ($idVendedor, $qtdValores, $ano) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->getVendasMensalFor($idVendedor, $ano, $qtdValores);
});

$app->get('/Vendedores/Perfil/{idVendedor}/Produtos', function ($idVendedor) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->getProdutosForVendedor($idVendedor);
});

$app->get('/Vendedores/Perfil/{idVendedor}/Produtos/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idVendedor, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->getProdutosForVendedor($idVendedor, $ano, $mes, $intervalo);
});


$app->get('/Vendedores/Perfil/{idVendedor}/Clientes', function ($idVendedor) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->getClientesForVendedor($idVendedor);
});

$app->get('/Vendedores/Perfil/{idVendedor}/Clientes/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idVendedor, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->getClientesForVendedor($idVendedor, $ano, $mes, $intervalo);
});

$app->get('/Vendedores/Perfil/{idVendedor}/Resumo', function ($idVendedor) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->getResumo($idVendedor);
});

$app->get('/Vendedores/Perfil/{idVendedor}/Resumo/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idVendedor, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->getResumo($idVendedor, $ano, $mes, $intervalo);
});

$app->get('/Vendedores/Perfil/{idVendedor}/Ano/{ano}/HistoricoValoresVenda', function ($idVendedor, $ano) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->getHistoricoValoresVenda($idVendedor, $ano);
});

$app->get('/Vendedores/Perfil/{idVendedor}/Ano/{ano}/HistoricoValoresMargem', function ($idVendedor, $ano) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->getHistoricoValoresMargem($idVendedor, $ano);
});
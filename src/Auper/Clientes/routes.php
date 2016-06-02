<?php

// ---------------------------- Clientes Todos
$app->get('/Clientes/De/{dataInicio}/Ate/{dataFim}/Status/{status}', function ($dataInicio, $dataFim, $status) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getTodosClientes($dataInicio, $dataFim, $status);
});

// ---------------------------- Clientes Status
$app->get('/Clientes/Positivos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getClientesPositivos($mes, $ano, $int);
});

$app->get('/Clientes/Regulares/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getClientesRegulares($mes, $ano, $int);
});


$app->get('/Clientes/Novos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getClientesNovos($mes, $ano, $int);
});

$app->get('/Clientes/Negativos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getClientesNegativos($mes, $ano, $int);
});

$app->get('/Clientes/Recuperados/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getClientesRecuperados($mes, $ano, $int);
});

// ---------------------------- Clientes Status Comparativo
$app->get('/Clientes/Comparativo/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getComparativoClientes($mes, $ano, $int);
});

$app->get('/Clientes/ComparativoHead/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getComparativoHeadClientes($mes, $ano, $int);
});

// Perfil do cliente
$app->get('/Clientes/Perfil/{idCliente}/{qtdValores}/Mensal/Ano/{ano}', function ($idCliente, $qtdValores, $ano) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getVendasMensalFor($idCliente, $ano, $qtdValores);
});

$app->get('/Clientes/Perfil/{idCliente}/Produtos', function ($idCliente) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getProdutosForCliente($idCliente);
});

$app->get('/Clientes/Perfil/{idCliente}/Produtos/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idCliente, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getProdutosForCliente($idCliente, $ano, $mes, $intervalo);
});


$app->get('/Clientes/Perfil/{idCliente}/Vendedores', function ($idCliente) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getVendedoresForCliente($idCliente);
});

$app->get('/Clientes/Perfil/{idCliente}/Vendedores/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idCliente, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getVendedoresForCliente($idCliente, $ano, $mes, $intervalo);
});

$app->get('/Clientes/Perfil/{idCliente}/Resumo', function ($idCliente) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getResumo($idCliente);
});

$app->get('/Clientes/Perfil/{idCliente}/Resumo/Ano/{ano}/Mes/{mes}/Intervalo/{intervalo}', function ($idCliente, $ano, $mes, $intervalo) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getResumo($idCliente, $ano, $mes, $intervalo);
});

$app->get('/Clientes/Perfil/{idCliente}/Ano/{ano}/HistoricoValoresCompra', function ($idCliente, $ano) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getHistoricoValoresCompra($idCliente, $ano);
});

$app->get('/Clientes/Perfil/{idCliente}/Ano/{ano}/HistoricoValoresVenda', function ($idCliente, $ano) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getHistoricoValoresVenda($idCliente, $ano);
});

$app->get('/Clientes/Perfil/{idCliente}/Ano/{ano}/HistoricoValoresMargem', function ($idCliente, $ano) use ($app) {
    $class = new Auper\Clientes\Controller\ClientesController($app);
    return $class->getHistoricoValoresMargem($idCliente, $ano);
});
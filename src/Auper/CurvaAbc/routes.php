<?php


// ------------------------------------- Clientes
$app->get('/CurvaAbc/VendasClientes/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasClientes\Controller\ClientesController($app);
    return $class->loadCurvaAbcClientes($mes, $ano, $int);
});

$app->get('/CurvaAbc/VendasClientes/Mes/{mes}/Ano/{ano}/Intervalo/{int}/Historico', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasClientes\Controller\ClientesController($app);
    return $class->loadCurvaAbcClientes($mes, $ano, $int, true);
});

$app->get('/CurvaAbc/VendasClientesHead/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasClientes\Controller\ClientesController($app);
    return $class->loadCurvaAbcClientesHead($mes, $ano, $int);
});

// ------------------------------------- Produtos
$app->get('/CurvaAbc/VendasProdutos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasProdutos\Controller\ProdutosController($app);
    return $class->loadCurvaAbcProdutos($mes, $ano, $int);
});

$app->get('/CurvaAbc/VendasProdutos/Mes/{mes}/Ano/{ano}/Intervalo/{int}/Historico', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasProdutos\Controller\ProdutosController($app);
    return $class->loadCurvaAbcProdutos($mes, $ano, $int, true);
});

$app->get('/CurvaAbc/VendasProdutosHead/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasProdutos\Controller\ProdutosController($app);
    return $class->loadCurvaAbcProdutosHead($mes, $ano, $int);
});

// ------------------------------------- Vendedores
$app->get('/CurvaAbc/VendasVendedores/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasVendedores\Controller\VendedoresController($app);
    return $class->loadCurvaAbcVendedores($mes, $ano, $int);
});

$app->get('/CurvaAbc/VendasVendedores/Mes/{mes}/Ano/{ano}/Intervalo/{int}/Historico', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasVendedores\Controller\VendedoresController($app);
    return $class->loadCurvaAbcVendedores($mes, $ano, $int, true);
});

$app->get('/CurvaAbc/VendasVendedoresHead/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasVendedores\Controller\VendedoresController($app);
    return $class->loadCurvaAbcVendedoresHead($mes, $ano, $int);
});

//--------------------------------- Forneccedores
$app->get('/CurvaAbc/ComprasFornecedores/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\ComprasFornecedores\Controller\FornecedoresController($app);
    return $class->loadCurvaAbcFornecedores($mes, $ano, $int);
});

$app->get('/CurvaAbc/ComprasFornecedores/Mes/{mes}/Ano/{ano}/Intervalo/{int}/Historico', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\ComprasFornecedores\Controller\FornecedoresController($app);
    return $class->loadCurvaAbcFornecedores($mes, $ano, $int, true);
});

$app->get('/CurvaAbc/ComprasFornecedoresHead/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\ComprasFornecedores\Controller\FornecedoresController($app);
    return $class->loadCurvaAbcFornecedoresHead($mes, $ano, $int);
});
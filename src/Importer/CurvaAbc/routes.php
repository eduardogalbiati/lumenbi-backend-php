<?php


/**
 * Curva ABC Mensal (Vendas Clientes)
 */
$app->get('/Importer/CurvaAbc/VendasClientes/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasClientes\Controller\ClientesController($app);
    return $class->importCurvaAbc($mes, $ano, $int);
});

/**
 * Curva ABC Anual (Vendas Clientes)
 */
$app->get('/Importer/CurvaAbc/VendasClientes/Ano/{ano}/Intervalo/{int}', function ($ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasClientes\Controller\ClientesController($app);
    for($x=1;$x<=12;$x++){
       $class->importCurvaAbc($x, $ano, $int);
    }
});

/**
 * Curva ABC Mensal (Vendas Produtos)
 */
$app->get('/Importer/CurvaAbc/VendasProdutos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasProdutos\Controller\ProdutosController($app);
    return $class->importCurvaAbc($mes, $ano, $int);
});

/**
 * Curva ABC Anual (Vendas Produtos)
 */
$app->get('/Importer/CurvaAbc/VendasProdutos/Ano/{ano}/Intervalo/{int}', function ($ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasProdutos\Controller\ProdutosController($app);
    for($x=1;$x<=12;$x++){
       $class->importCurvaAbc($x, $ano, $int);
    }
});

/**
 * Curva ABC Mensal (Compras Produtos)
 */
$app->get('/Importer/CurvaAbc/ComprasProdutos/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\ComprasProdutos\Controller\ProdutosController($app);
    return $class->importCurvaAbc($mes, $ano, $int);
});

/**
 * Curva ABC Anual (Compras Produtos)
 */
$app->get('/Importer/CurvaAbc/ComprasProdutos/Ano/{ano}/Intervalo/{int}', function ($ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\ComprasProdutos\Controller\ProdutosController($app);
    for($x=1;$x<=12;$x++){
       $class->importCurvaAbc($x, $ano, $int);
    }
});


/**
 * Curva ABC Mensal (Vendas Vendedores)
 */
$app->get('/Importer/CurvaAbc/VendasVendedores/Mes/{mes}/Ano/{ano}/Intervalo/{int}', function ($mes, $ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasVendedores\Controller\VendedoresController($app);
    return $class->importCurvaAbc($mes, $ano, $int);
});


/**
 * Curva ABC Anual (Vendas Vendedores)
 */
$app->get('/Importer/CurvaAbc/VendasVendedores/Ano/{ano}/Intervalo/{int}', function ($ano, $int) use ($app) {
    $class = new Auper\CurvaAbc\VendasVendedores\Controller\VendedoresController($app);
    for($x=1;$x<=12;$x++){
       $class->importCurvaAbc($x, $ano, $int);
    }
});
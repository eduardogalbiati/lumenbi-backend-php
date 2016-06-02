<?php

/**
 * Tabela de Apoio
 */
$app->get('/Importer/Vendedores/AtualizaDesde/{datainicio}', function ($datainicio) use ($app) {
    $class = new Auper\Vendedores\Controller\VendedoresController($app);
    return $class->importVendedores($datainicio);
});


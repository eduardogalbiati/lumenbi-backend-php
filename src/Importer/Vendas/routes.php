<?php

/**
 * Importação Geral de Vendas !!!!
 */
$app->get('/Importer/Vendas', function () use ($app) {
    $class = new Auper\Vendas\Controller\VendasController($app);
    return $class->importResumoMensal();
});
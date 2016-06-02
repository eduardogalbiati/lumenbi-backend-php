<?php

/**
 * Importação Geral do estoque !!!!
 */
$app->get('/Importer/Estoque', function () use ($app) {
    $class = new Auper\Estoque\Controller\EstoqueController($app);
    return $class->importEstoque();
});
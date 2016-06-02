<?php

/**
 * Todas Compras
 */
$app->get('/Importer/Compras', function () use ($app) {
    $class = new Auper\Compras\Controller\ComprasController($app);
    return $class->importResumoMensal();
});
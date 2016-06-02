<?php

$app->get('/LumemBiCore/Importers/Clientes/Check', function () use ($app) {
    $class = new \LumenBiCore\Importers\Clientes\Controller\ClientesController($app);
    return $class->checkClientes();
});

$app->get('/LumemBiCore/Importers/Clientes/Drop', function () use ($app) {
    $class = new \LumenBiCore\Importers\Clientes\Controller\ClientesController($app);
    return $class->dropClientes();
});

$app->get('/LumemBiCore/Importers/Clientes/Import', function () use ($app) {
    $class = new \LumenBiCore\Importers\Clientes\Controller\ClientesController($app);
    return $class->importClientes();
});
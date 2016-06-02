<?php

namespace LumenBiCore\Importers\Clientes\Controller;

use Silex\Application;
use Core\Response\ImporterResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ClientesController
{
	protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

	public function checkClientes()
	{

		$cDm = new \Auper\Clientes\DataMapper\ClientesDataMapper($this->app['db']);
		$count = $cDm->getCountClientes($ativo = 'S');

		$res = new ImporterResponse();
		$ret = $res->setData(Array(
			'count'=> $count
			))
		->getResponse();

		return $this->app->json($ret);


	}

	public function importClientes()
	{

		$subRequest = Request::create('/Importer/Clientes', 'GET');
		
		$response = $this->app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
		
		return($response);
	}

	public function dropClientes()
	{
		$cDm = new \Auper\Clientes\DataMapper\ClientesDataMapper($this->app['db']);
		$countOld = $cDm->getCountClientes($ativo = 'S');

		$delete = $cDm->dropClientes();

		$newCount = $cDm->getCountClientes($ativo = 'S'	);

		$res = new ImporterResponse();
		$ret = $res->setData(
			array(
				'totalBefore' => $countOld,
				'totalAfter' => $newCount
				)
			)->getResponse();

		return $this->app->json($ret);
	}
}
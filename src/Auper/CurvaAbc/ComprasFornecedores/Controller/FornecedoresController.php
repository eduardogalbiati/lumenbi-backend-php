<?Php

namespace Auper\CurvaAbc\ComprasFornecedores\Controller;

use Silex\Application;

use Auper\Compras\DataMapper\ComprasQtdValoresFornecedoresDataMapper;

use Auper\CurvaAbc\ComprasFornecedores\Translator\CurvaAbcComprasFornecedoresTranslator;
use Auper\CurvaAbc\ComprasFornecedores\Translator\CurvaAbcComprasFornecedoresHeadTranslator;

use Auper\CurvaAbc\ComprasFornecedores\DataMapper\CurvaAbcComprasFornecedoresDataMapper;
use Auper\CurvaAbc\ComprasFornecedores\DataMapper\CurvaAbcComprasFornecedoresHeadDataMapper;

class FornecedoresController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function importCurvaAbc($mes, $ano, $int)
    {
        $vqvpd = new ComprasQtdValoresFornecedoresDataMapper($this->app['db']);
        $return = $vqvpd->loadAbc($mes, $ano, $int);

        $abc = $this->app['curvaAbc'];

        foreach ($return as $fornecedor) {
            $abc->addLinha($fornecedor['idFornecedor'], $fornecedor['sumQtd'], ($fornecedor['sumValor'] / $fornecedor['sumQtd']),$fornecedor);
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();
        $arr['info']['mes'] = $mes;
        $arr['info']['ano'] = $ano; 
        $arr['info']['int'] = $int; 


        $translator = new CurvaAbcComprasFornecedoresTranslator();
        $result =$translator->translate($arr);

        $dataMapper = new CurvaAbcComprasFornecedoresDataMapper($this->app['db']);
        $dataMapper->insert($result);

        $translator = new CurvaAbcComprasFornecedoresHeadTranslator();
        $result =$translator->translate($arr);

        $dataMapper = new CurvaAbcComprasFornecedoresHeadDataMapper($this->app['db']);
        $dataMapper->insert($result);

        return $this->app->json($arr);
    }

    

    public function loadCurvaAbcFornecedores($mes, $ano, $int, $history = false)
    {
        $vqvpd = new ComprasQtdValoresFornecedoresDataMapper($this->app['db']);
        $fornecedores = $vqvpd->loadAbc($mes, $ano, $int);


        $abc = $this->app['curvaAbc'];

        if($history === true){

            $dm = new CurvaAbcComprasFornecedoresDataMapper($this->app['db']);
            $historico = $dm->loadHistoricoAntesDe($mes, $ano, $int);
            $abc->addHistorico($historico);
        }

        $statusDm = new \Auper\Fornecedores\DataMapper\FornecedoresStatusDataMapper($this->app['db']);
        $forStatus = $statusDm->loadFornecedoresStatus($mes, $ano, $int);

        $forFetcher = new \Auper\Fornecedores\Fetcher\FornecedoresStatusFetcher();
        $forFetcher->setInfoToFetch($forStatus);
        $fornecedores = $forFetcher->fetch($fornecedores);

        foreach ($fornecedores as $fornecedor) {
            $abc->addLinha($fornecedor['nomeFornecedor'], $fornecedor['sumQtd'], ($fornecedor['sumValor'] / $fornecedor['sumQtd']),$fornecedor);
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();
        $arr['info']['mes'] = $mes;
        $arr['info']['ano'] = $ano; 

        return $this->app->json($arr);
    }

    public function loadCurvaAbcFornecedoresHead($mes, $ano, $int)
    {

        $dataMapper = new CurvaAbcComprasFornecedoresHeadDataMapper($this->app['db']);
        $head = $dataMapper->loadHead($mes, $ano, $int);
        return $this->app->json($head);

    }



}

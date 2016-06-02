<?Php

namespace Auper\Fornecedores\Controller;

use Silex\Application;

use Auper\Fornecedores\DataMapper\FornecedoresDataMapper;
use Auper\Fornecedores\DataMapper\FornecedoresStatusDataMapper;
use Auper\Fornecedores\DataMapper\FornecedoresStatusHeadDataMapper;

use Auper\Fornecedores\DataMapper\ExternalFornecedoresDataMapper;
use Auper\Fornecedores\Translator\ExternalFornecedoresTranslator;

use Auper\Fornecedores\Translator\FornecedoresStatusTranslator;
use Auper\Fornecedores\Translator\FornecedoresStatusHeadTranslator;

use Auper\CurvaAbc\ComprasFornecedores\DataMapper\CurvaAbcComprasFornecedoresDataMapper;

class FornecedoresController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function importFornecedoresStatus($mesAlvo, $anoAlvo, $intervalo)
    {

        $fornecedoresDM = new FornecedoresDataMapper($this->app['db']);
        $fornecedores = $fornecedoresDM->loadFornecedoresForStatus($mesAlvo, $anoAlvo, $intervalo);

        $cliStatus = new \Core\Utils\FornecedoresStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => $mesAlvo,
                'ano' => $anoAlvo,
                'int' => $intervalo,
            ));

        $fornecedores =  $cliStatus->generate($fornecedores);

        $fornecedoresStatusTR = new FornecedoresStatusHeadTranslator($this->app['db']);
        $translated = $fornecedoresStatusTR->translateToInsert($fornecedores);
        $fornecedoresStatusDM = new FornecedoresStatusHeadDataMapper($this->app['db']);
        $fornecedoresStatusDM->insert($translated);

        $fornecedoresStatusTR = new FornecedoresStatusTranslator($this->app['db']);
        $translated = $fornecedoresStatusTR->translateToInsert($fornecedores);

        $fornecedoresStatusDM = new FornecedoresStatusDataMapper($this->app['db']);
        $fornecedoresStatusDM->insert($translated);



        return $this->app->json($translated);

    }

    public function importFornecedores($dataInicio = '00/00/00')
    {
        $dm = new ExternalFornecedoresDataMapper($this->app['db']);
        $fornecedores = $dm->loadFornecedores();

        $tr = new ExternalFornecedoresTranslator();
        $translated = $tr->translate($fornecedores);

        $dm2 = new FornecedoresDataMapper($this->app['db']);
        $dm2->insert($translated);

        //$dm = new ExternalFornecedoresDataMapper($this->app['db']);
        //$fornecedores = $dm->loadFornecedoresForUpdate($dataInicio);

        //$tr = new ExternalFornecedoresTranslator();
        //$translated = $tr->translate($fornecedores);

        //$dm2 = new FornecedoresDataMapper($this->app['db']);
        //$dm2->update($translated);

        return $this->app->json($translated);

    }

    // compram a N meses
    public function getFornecedoresPositivos($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new FornecedoresStatusDataMapper($this->app['db']);
        $return = $dm->loadFornecedoresPositivos($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

     public function getFornecedoresRegulares($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new FornecedoresStatusDataMapper($this->app['db']);
        $return = $dm->loadFornecedoresRegulares($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

    //Fizeram a primeira compra só
    public function getFornecedoresNovos($mesAlvo, $anoAlvo, $intervalo)
    {
        
        $dm = new FornecedoresStatusDataMapper($this->app['db']);
        $return = $dm->loadFornecedoresNovos($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }
    //Fornecedores que não compra a N meses
    public function getFornecedoresNegativos($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new FornecedoresStatusDataMapper($this->app['db']);
        $return = $dm->loadFornecedoresNegativos($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

    //fizeram compra mais fazia N meses que não compravamx
    public function getFornecedoresRecuperados($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new FornecedoresStatusDataMapper($this->app['db']);
        $return = $dm->loadFornecedoresRecuperados($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

     public function getTodosFornecedores($dataInicio, $dataFim, $ativo)
    {
        $dm = new FornecedoresDataMapper($this->app['db']);
        $return = $dm->loadTodos($dataInicio, $dataFim, $ativo);

        return $this->app->json($return);
    }

    public function getComparativoFornecedores($mesAlvo, $anoAlvo, $intervalo)
    {
       
        $dm = new FornecedoresStatusHeadDataMapper($this->app['db']);
        $ret = $dm->loadFornecedoresStatusComparativoHead($mesAlvo, $anoAlvo, $intervalo);

        //calculando as porcentagens
        $arr['table'] = $ret;
        $total = $ret['itens']['total'];
        unset($ret['itens']['total']);

        foreach($ret['itens'] as $tipo => $qtd){
            //if($tipo != 'neg'){
           // $itemPizza = array();
            //$itemPizza['label'] = $array[$tipo];
            //$itemPizza['data'] = round($qtd * 100 / $ret['total']);
            $arr['pizza']['seq'][] = $tipo;
            $arr['pizza']['chart'][] = round($qtd * 100 / $total);
            $arr['pizza']['info'][] = $qtd;
           // }
        }
       

        $arr['pizza']['total'] = $total;
        return $this->app->json($arr);
     
    }
    public function getComparativoHeadFornecedores($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new FornecedoresStatusHeadDataMapper($this->app['db']);
        $ret = $dm->loadFornecedoresStatusHead($mesAlvo, $anoAlvo, $intervalo);
        return $this->app->json($ret);
     
    }

     public function getVendasMensalFor($idFornecedor, $ano, $qtdValores)
    {
        $arrDom = array(
            'Valores' => 'valorTotal',
            'Quantidades' => 'qtdTotal',
            );
        
        $indice = $arrDom[$qtdValores];
        $dm = new FornecedoresDataMapper($this->app['db']);
        $ret = $dm->loadComprasForFornecedor($idFornecedor, $ano);

        foreach ($ret as $item) {
            $arr[] = array($item['mes'],$item[$indice]);
        }
        return $this->app->json($arr);
    }

     public function getResumo($idFornecedor, $ano = false, $mes = false, $intervalo= false)
    {

        if($intervalo == false){
            $intervalo = 3;
        }
        $dm = new FornecedoresDataMapper($this->app['db']);
        $ret['fornecedor'] = $dm->loadById($idFornecedor);

        $dm2 = new FornecedoresStatusDataMapper($this->app['db']);
        $ret['status'] = $dm2->loadStatusHistorico($idFornecedor, $intervalo);

        $ret['fornecedor']['status'] = $ret['status'][0]['idStatus'];
        $dm3 = new CurvaAbcComprasFornecedoresDataMapper($this->app['db']);
        $ret['classe'] = $dm3->loadClasseHistorico($idFornecedor, $intervalo);
        $ret['fornecedor']['classe'] = $ret['classe'][0]['class'];
        $ret['fornecedor']['posicao'] = $ret['classe'][0]['pos'];

        $dm = new \Auper\Compras\DataMapper\ComprasQtdValoresProdutosFornecedoresDataMapper($this->app['db']);
        $ret['fornecedor']['produtosDistintos'] = $dm->loadProdutosDistintos($idFornecedor, $ano, $mes);
        $ret['fornecedor']['qtdComprada'] = $dm->loadQtdCompradaForFornecedor($idFornecedor, $ano, $mes);

        return $this->app->json($ret);

    }

    public function getHistoricoValoresCompra($idFornecedor, $ano)
    {
        $dm = new \Auper\Compras\DataMapper\ComprasQtdValoresFornecedoresDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresCompra($idFornecedor, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['sumValor']  / $tupla['sumQtd']),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getHistoricoValoresVenda($idFornecedor, $ano)
    {
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresClientesDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresVenda($idFornecedor, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['sumValor']  / $tupla['sumQtd']),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getHistoricoValoresMargem($idFornecedor, $ano)
    {
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresClientesDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresMargem($idFornecedor, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['avgMargem']*100),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getProdutosForFornecedor($idFornecedor, $ano = false, $mes = false, $intervalo = false)
    {

        if($ano == false || $mes == false){
            $date = new \DateTime(date("Y-m-d"));
        }else{
            $ultimoDiaMesAlvo = date("t", mktime(0,0,0,$mes,'01',$ano));
            $date = new \DateTime($ano."-".$mes."-".$ultimoDiaMesAlvo);
        }

        if($intervalo == false){
            $intervalo = 3;
        }

        $dateOp = new \Core\Utils\DateOperation($date);
        
       // $dateOp->subMonth(1);
        $mesStatus = $dateOp->getMonth();
        $anoStatus = $dateOp->getYear();

        $dateOp->subMonth(1);
        $mesAbc = $dateOp->getMonth();
        $anoAbc = $dateOp->getYear();

        //Carregando Clientes
        $dm = new \Auper\Compras\DataMapper\ComprasQtdValoresProdutosFornecedoresDataMapper($this->app['db']);
        $ret = $dm->loadProdutosForFornecedor($idFornecedor, $ano, $mes);

        //Carregando suas classes
        $dmAbc = new \Auper\CurvaAbc\VendasProdutos\DataMapper\CurvaAbcVendasProdutosDataMapper($this->app['db']);
        $classes = $dmAbc->loadClasses($mesAbc, $anoAbc, $intervalo);

      
        $classeFetcher = new \Auper\Produtos\Fetcher\ProdutosClasseFetcher();
        $classeFetcher->setInfoToFetch($classes);
        $ret = $classeFetcher->fetch($ret);

        //Carregando seus status
        $dmStatus = new \Auper\Produtos\DataMapper\ProdutosStatusDataMapper($this->app['db']);
        $status = $dmStatus->loadProdutosStatus($mesStatus, $anoStatus, $intervalo);

        $statusFetcher = new \Auper\Produtos\Fetcher\ProdutosStatusFetcher();
        $statusFetcher->setInfoToFetch($status);
        $ret = $statusFetcher->fetch($ret);



        return $this->app->json($ret);
    }


    
}

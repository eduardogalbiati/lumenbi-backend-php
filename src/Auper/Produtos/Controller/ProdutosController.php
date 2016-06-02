<?Php

namespace Auper\Produtos\Controller;

//use Auper\Produtos\Model\ProdutosModel;
use Silex\Application;

use Auper\Produtos\DataMapper\ProdutosDataMapper;
use Auper\Produtos\DataMapper\ProdutosStatusDataMapper;
use Auper\Produtos\DataMapper\ProdutosStatusHeadDataMapper;

use Auper\Produtos\DataMapper\ExternalProdutosDataMapper;
use Auper\Produtos\Translator\ExternalProdutosTranslator;

use Auper\Produtos\Translator\ProdutosStatusTranslator;
use Auper\Produtos\Translator\ProdutosStatusHeadTranslator;

use Auper\CurvaAbc\VendasProdutos\DataMapper\CurvaAbcVendasProdutosDataMapper;

class ProdutosController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


     public function importProdutosStatus($mesAlvo, $anoAlvo, $intervalo)
    {
        $produtosDM = new ProdutosDataMapper($this->app['db']);
        $produtos = $produtosDM->loadProdutosForStatus($mesAlvo, $anoAlvo, $intervalo);

        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => $mesAlvo,
                'ano' => $anoAlvo,
                'int' => $intervalo,
            ));
        
        $produtos =  $cliStatus->generate($produtos);

        $produtosStatusTR = new ProdutosStatusHeadTranslator($this->app['db']);
        $translated = $produtosStatusTR->translateToInsert($produtos);
        $produtosStatusDM = new ProdutosStatusHeadDataMapper($this->app['db']);
        $produtosStatusDM->insert($translated);

        $produtosStatusTR = new ProdutosStatusTranslator($this->app['db']);
        $translated = $produtosStatusTR->translateToInsert($produtos);

        $produtosStatusDM = new ProdutosStatusDataMapper($this->app['db']);
        $produtosStatusDM->insert($translated);



        return $this->app->json($translated);

    }


    public function importProdutos()
    {
            $dm = new ExternalProdutosDataMapper($this->app['db']);
            $produtos = $dm->loadProdutos();
           
            $tr = new ExternalProdutosTranslator();
            $translated = $tr->translate($produtos);

            $dm2 = new ProdutosDataMapper($this->app['db']);
            $dm2->insert($translated);

            return $this->app->json($translated);

    }


    // compram a N meses
    public function getProdutosPositivos($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new ProdutosStatusDataMapper($this->app['db']);
        $return = $dm->loadProdutosPositivos($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

     public function getProdutosRegulares($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new ProdutosStatusDataMapper($this->app['db']);
        $return = $dm->loadProdutosRegulares($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

    //Fizeram a primeira compra só
    public function getProdutosNovos($mesAlvo, $anoAlvo, $intervalo)
    {
        
        $dm = new ProdutosStatusDataMapper($this->app['db']);
        $return = $dm->loadProdutosNovos($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }
    //Produtos que não compra a N meses
    public function getProdutosNegativos($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new ProdutosStatusDataMapper($this->app['db']);
        $return = $dm->loadProdutosNegativos($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

    //fizeram compra mais fazia N meses que não compravamx
    public function getProdutosRecuperados($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new ProdutosStatusDataMapper($this->app['db']);
        $return = $dm->loadProdutosRecuperados($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

     public function getTodosProdutos($dataInicio, $dataFim, $ativo)
    {
        $dm = new ProdutosDataMapper($this->app['db']);
        $return = $dm->loadTodos($dataInicio, $dataFim, $ativo);

        return $this->app->json($return);
    }

    public function getComparativoProdutos($mesAlvo, $anoAlvo, $intervalo)
    {
       
        $dm = new ProdutosStatusHeadDataMapper($this->app['db']);
        $ret = $dm->loadProdutosStatusComparativoHead($mesAlvo, $anoAlvo, $intervalo);

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
    public function getComparativoHeadProdutos($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new ProdutosStatusHeadDataMapper($this->app['db']);
        $ret = $dm->loadProdutosStatusHead($mesAlvo, $anoAlvo, $intervalo);
        return $this->app->json($ret);
     
    }

    public function getVendasMensalFor($idProduto, $ano, $qtdValores)
    {
        $arrDom = array(
            'Valores' => 'valorTotal',
            'Quantidades' => 'qtdTotal',
            );
        
        $indice = $arrDom[$qtdValores];
        $dm = new ProdutosDataMapper($this->app['db']);
        $ret = $dm->loadVendasForProduto($idProduto, $ano);

        foreach ($ret as $item) {
            $arr[] = array($item['mes'],$item[$indice]);
        }
        return $this->app->json($arr);
    }

    public function getResumo($idProduto, $ano = false, $mes = false, $intervalo= false)
    {

        if($intervalo == false){
            $intervalo = 3;
        }
        $dm = new ProdutosDataMapper($this->app['db']);
        $ret['produto'] = $dm->loadById($idProduto);

        $dm2 = new ProdutosStatusDataMapper($this->app['db']);
        $ret['status'] = $dm2->loadStatusHistorico($idProduto, $intervalo);

        $ret['produto']['status'] = $ret['status'][0]['idStatus'];
        $dm3 = new CurvaAbcVendasProdutosDataMapper($this->app['db']);
        $ret['classe'] = $dm3->loadClasseHistorico($idProduto, $intervalo);
        $ret['produto']['classe'] = $ret['classe'][0]['class'];
        $ret['produto']['posicao'] = $ret['classe'][0]['pos'];

        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosClientesDataMapper($this->app['db']);
        $ret['produto']['clientesDistintos'] = $dm->loadClientesDistintos($idProduto, $ano, $mes);
        $ret['produto']['qtdVendida'] = $dm->loadQtdVendidaForProduto($idProduto, $ano, $mes);

        return $this->app->json($ret);

    }

    public function getHistoricoValoresCompra($idProduto, $ano)
    {
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresCompra($idProduto, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['sumValor']  / $tupla['sumQtd']),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getHistoricoValoresVenda($idProduto, $ano)
    {
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresVenda($idProduto, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['sumValor']  / $tupla['sumQtd']),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getHistoricoValoresMargem($idProduto, $ano)
    {
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresMargem($idProduto, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['avgMargem']*100),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getClientesForProduto($idProduto, $ano = false, $mes = false, $intervalo = false)
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
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosClientesDataMapper($this->app['db']);
        $ret = $dm->loadClientesForProduto($idProduto, $ano, $mes);

        //Carregando suas classes
        $dmAbc = new \Auper\CurvaAbc\VendasClientes\DataMapper\CurvaAbcVendasClientesDataMapper($this->app['db']);
        $classes = $dmAbc->loadClasses($mesAbc, $anoAbc, $intervalo);

        $classeFetcher = new \Auper\Clientes\Fetcher\ClientesClasseFetcher();
        $classeFetcher->setInfoToFetch($classes);
        $ret = $classeFetcher->fetch($ret);

        //Carregando seus status
        $dmStatus = new \Auper\Clientes\DataMapper\ClientesStatusDataMapper($this->app['db']);
        $status = $dmStatus->loadClientesStatus($mesStatus, $anoStatus, $intervalo);

        $statusFetcher = new \Auper\Clientes\Fetcher\ClientesStatusFetcher();
        $statusFetcher->setInfoToFetch($status);
        $ret = $statusFetcher->fetch($ret);



        return $this->app->json($ret);
    }

    public function getFornecedoresForProduto($idProduto, $ano = false, $mes = false, $intervalo = false)
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
        $ret = $dm->loadFornecedoresForProduto($idProduto, $ano, $mes);

        //Carregando suas classes
        $dmAbc = new \Auper\CurvaAbc\ComprasFornecedores\DataMapper\CurvaAbcComprasFornecedoresDataMapper($this->app['db']);
        $classes = $dmAbc->loadClasses($mesAbc, $anoAbc, $intervalo);

        $classeFetcher = new \Auper\Fornecedores\Fetcher\FornecedoresClasseFetcher();
        $classeFetcher->setInfoToFetch($classes);
        $ret = $classeFetcher->fetch($ret);

        //Carregando seus status
        $dmStatus = new \Auper\Fornecedores\DataMapper\FornecedoresStatusDataMapper($this->app['db']);
        $status = $dmStatus->loadFornecedoresStatus($mesStatus, $anoStatus, $intervalo);

        $statusFetcher = new \Auper\Fornecedores\Fetcher\FornecedoresStatusFetcher();
        $statusFetcher->setInfoToFetch($status);
        $ret = $statusFetcher->fetch($ret);



        return $this->app->json($ret);
    }

    public function getVendedoresForProduto($idProduto, $ano = false, $mes = false, $intervalo = false)
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

        //Carregando Vendedores
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosVendedoresDataMapper($this->app['db']);
        $ret = $dm->loadVendedoresForProduto($idProduto, $ano, $mes);

        //Carregando suas classes
        $dmAbc = new \Auper\CurvaAbc\VendasVendedores\DataMapper\CurvaAbcVendasVendedoresDataMapper($this->app['db']);
        $classes = $dmAbc->loadClasses($mesAbc, $anoAbc, $intervalo);

        $classeFetcher = new \Auper\Vendedores\Fetcher\VendedoresClasseFetcher();
        $classeFetcher->setInfoToFetch($classes);
        $ret = $classeFetcher->fetch($ret);

        return $this->app->json($ret);
    }


}

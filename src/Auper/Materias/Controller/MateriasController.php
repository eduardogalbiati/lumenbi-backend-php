<?Php

namespace Auper\Materias\Controller;

use Silex\Application;

use Auper\Materias\DataMapper\MateriasDataMapper;
//use Auper\Materias\DataMapper\MateriasStatusDataMapper;
//use Auper\Materias\DataMapper\MateriasStatusHeadDataMapper;
use Auper\Materias\DataMapper\ExternalMateriasDataMapper;
use Auper\Materias\Translator\ExternalMateriasTranslator;
//use Auper\Materias\Translator\MateriasPositivosTranslator;
//use Auper\Materias\Translator\MateriasNovosTranslator;
//use Auper\Materias\Translator\MateriasRecuperadosTranslator;
//use Auper\Materias\Translator\MateriasNegativosTranslator;
//use Auper\Materias\Translator\MateriasStatusTranslator;


class MateriasController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function importMateriasStatus($mesAlvo, $anoAlvo, $intervalo)
    {
        $materiasDM = new MateriasDataMapper($this->app['db']);
        $ret = $materiasDM->loadComparativoMaterias($mesAlvo, $anoAlvo, $intervalo);

        $materiasStatusTR = new MateriasStatusTranslator($this->app['db']);
        $translated = $materiasStatusTR->translate($ret);

        $materiasStatusHeadDM = new MateriasStatusHeadDataMapper($this->app['db']);
        $ret = $materiasStatusHeadDM->insert($translated);

        $materiasStatusDM = new MateriasStatusDataMapper($this->app['db']);

        $cPos = $materiasDM->loadMateriasPositivos($mesAlvo, $anoAlvo, $intervalo);
        $cPosTr = new MateriasPositivosTranslator();
        $trCpos = $cPosTr->translate($cPos, $mesAlvo, $anoAlvo, $intervalo);
        $materiasStatusDM->insert($trCpos);

        $cNeg = $materiasDM->loadMateriasNegativos($mesAlvo, $anoAlvo, $intervalo);
        $cNegTr = new MateriasNegativosTranslator();
        $trCneg = $cNegTr->translate($cNeg, $mesAlvo, $anoAlvo, $intervalo);
        $materiasStatusDM->insert($trCneg);

        $cRec = $materiasDM->loadMateriasRecuperados($mesAlvo, $anoAlvo, $intervalo);
        $cRecTr = new MateriasRecuperadosTranslator();
        $trCrec = $cRecTr->translate($cRec, $mesAlvo, $anoAlvo, $intervalo);
        $materiasStatusDM->insert($trCrec);

        $cNov = $materiasDM->loadMateriasNovos($mesAlvo, $anoAlvo, $intervalo);
        $cNovTr = new MateriasNovosTranslator();
        $trCnov = $cNovTr->translate($cNov, $mesAlvo, $anoAlvo, $intervalo);
        $materiasStatusDM->insert($trCnov);

        return $this->app->json($translated);

    }

    public function importMaterias()
    {
        $dm = new ExternalMateriasDataMapper($this->app['db']);
        $materias = $dm->loadMaterias();

        $tr = new ExternalMateriasTranslator();
        $translated = $tr->translate($materias);

        $dm2 = new MateriasDataMapper($this->app['db']);
        $dm2->insert($translated);

        return $this->app->json($translated);

    }

    // compram a N meses
    public function getMateriasPositivos($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new MateriasStatusDataMapper($this->app['db']);
        $return = $dm->loadMateriasPositivos($mesAlvo, $anoAlvo, $intervalo);

        $tr = new MateriasPositivosTranslator();
        $return = $tr->tableTranslate($return, $meses);

        return $this->app->json($return);
    }

    //Fizeram a primeira compra só
    public function getMateriasNovos($mesAlvo, $anoAlvo, $intervalo)
    {
        
        $dm = new MateriasStatusDataMapper($this->app['db']);
        $return = $dm->loadMateriasNovos($mesAlvo, $anoAlvo, $intervalo);

        $tr = new MateriasNovosTranslator();
        $return = $tr->tableTranslate($return, $meses);

        return $this->app->json($return);
    }
    //Materias que não compra a N meses
    public function getMateriasNegativos($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new MateriasStatusDataMapper($this->app['db']);
        $return = $dm->loadMateriasNegativos($mesAlvo, $anoAlvo, $intervalo);

        $tr = new MateriasNegativosTranslator();
        $return = $tr->tableTranslate($return, $meses);

        return $this->app->json($return);
    }

    //fizeram compra mais fazia N meses que não compravamx
    public function getMateriasRecuperados($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new MateriasStatusDataMapper($this->app['db']);
        $return = $dm->loadMateriasRecuperados($mesAlvo, $anoAlvo, $intervalo);

        $tr = new MateriasRecuperadosTranslator();
        $return = $tr->tableTranslate($return, $meses);

        return $this->app->json($return);
    }

     public function getTodosMaterias($dataInicio, $dataFim, $ativo)
    {
        $dm = new MateriasDataMapper($this->app['db']);
        $return = $dm->loadTodos($dataInicio, $dataFim, $ativo);

        return $this->app->json($return);
    }

    public function getComparativoMaterias($mesAlvo, $anoAlvo, $intervalo)
    {
       
        $dm = new MateriasStatusDataMapper($this->app['db']);
        $ret = $dm->loadComparativoMaterias($mesAlvo, $anoAlvo, $intervalo);

        //calculando as porcentagens
        $arr['table'] = $ret;
        $total = $ret['total'];
        foreach($ret['itens'] as $tipo => $qtd){
            //if($tipo != 'neg'){
           // $itemPizza = array();
            //$itemPizza['label'] = $array[$tipo];
            //$itemPizza['data'] = round($qtd * 100 / $ret['total']);
            $arr['pizza']['seq'][] = $tipo;
            $arr['pizza']['chart'][] = round($qtd * 100 / $ret['total']);
            $arr['pizza']['info'][] = $qtd;
            $total -= $qtd;
           // }
        }
       

        $itemPizza = array();
        $arr['pizza']['seq'][] = $tipo;
        $arr['pizza']['chart'][] = round($total *100 / $ret['total']);
        $arr['pizza']['info'][] = $total;
        //$arr['pizza'][] = $itemPizza;

       // $arr['pizza'] = $pizza;
        return $this->app->json($arr);
     
    }
    public function getComparativoHeadMaterias($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new MateriasStatusHeadDataMapper($this->app['db']);
        $ret = $dm->loadMateriasStatusHead($mesAlvo, $anoAlvo, $intervalo);

       

        return $this->app->json($ret);
     
    }

    
}

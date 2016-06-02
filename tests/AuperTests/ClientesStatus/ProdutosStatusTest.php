<?Php

namespace AuperTests\CurvaAbc;



class ProdutosStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Silex\Application $app
     */ 
    protected $app;

    function setUp()
    {
        parent::setUp();
        $this->app = createApplication();

    }

    public function assertPreConditions()
    {
       
        $this->assertTrue(
                class_exists($class = '\Core\Utils\CurvaAbc'),
                'Class not found: '.$class
        );

        $this->assertInstanceOf('\Silex\Application', $this->app);
        
    }

    

    public function testPreparingArrayIntervaloTresRepeticaoTres()
    {

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '8',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '7',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '6',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '8',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->prepareArray($clientes);
      //  var_dump($clientes);die;

        $this->assertEquals($clientes['1']['qtdPeriodo'] , '3');
        $this->assertEquals($clientes['1']['valorPeriodo'] , '6');
        $this->assertEquals($clientes['1']['ultimaData'] , '2015-08-01');
        $this->assertEquals($clientes['1']['nomeProduto'] , 'Cliente de Teste');
        $this->assertEquals($clientes['1']['idProduto'] , '1');
        $this->assertEquals($clientes['1']['vendas'][0]['ano'] , '2015');
        $this->assertEquals($clientes['1']['vendas'][0]['mes'] , '8');
        $this->assertEquals($clientes['1']['vendas'][1]['ano'] , '2015');
        $this->assertEquals($clientes['1']['vendas'][1]['mes'] , '7');
        $this->assertEquals($clientes['1']['vendas'][2]['ano'] , '2015');
        $this->assertEquals($clientes['1']['vendas'][2]['mes'] , '6');


    }
    public function testPositivoSemCompraNoMesAlvo()
    {

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '8',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '7',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '6',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;

        $this->assertEquals($clientes['resumo']['nPos'] , '1');
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '3');


    }

    public function testPositivoComCompraNoMesAlvo()
    {

         $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '8',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '7',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '6',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-09-01',
            );
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;
        $this->assertEquals($clientes['resumo']['nPos'] , '1');
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '4');


    }

    public function testRegularComCompraNoMesAlvo()
    {

         $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-09-01',
            );
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '8',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        /*
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '7',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
            */
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '6',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;
        $this->assertEquals($clientes['resumo']['nReg'] , '1');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '');


    }

    public function testRegularSemCompraNoMesAlvo()
    {
/*
         $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
            */
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '8',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        /*
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '7',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
            */
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '6',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;
        $this->assertEquals($clientes['resumo']['nReg'] , '1');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '');


    }

     public function testRegularComCompraNoMesLimite()
    {
/*
         $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
           
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '8',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            ); */
        /*
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '7',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
            */
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '6',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;
        $this->assertEquals($clientes['resumo']['nReg'] , '1');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '');


    }

    public function testNegativoNoMesLimite()
    {

         $clientes[] = array(
            'ano' => '2015',
            'mes' => '5',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
           
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '4',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
      
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '3',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
           
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '2',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '1');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '4');


    }

    public function testNegativoAntesDoMesLimite()
    {

         $clientes[] = array(
            'ano' => '2015',
            'mes' => '4',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
           
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '3',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
      
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '2',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
           
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '1',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '1');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '5');


    }

   public function testNegativoAnoPassado()
    {

         $clientes[] = array(
            'ano' => '2014',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2014-09-01',
            );
           
        $clientes[] = array(
            'ano' => '2014',
            'mes' => '8',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2014-09-01',
            );
      
        $clientes[] = array(
            'ano' => '2014',
            'mes' => '7',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2014-09-01',
            );
           
        $clientes[] = array(
            'ano' => '2014',
            'mes' => '6',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2014-09-01',
            );
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '1');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '12');


    }

    public function testNegativoViradaDoAnoDez()
    {

         $clientes[] = array(
            'ano' => '2014',
            'mes' => '12',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2014-09-01',
            );
           
       
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '5',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '1');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '5');


    }

    public function testNegativoViradaDoAnoNov()
    {

         $clientes[] = array(
            'ano' => '2014',
            'mes' => '11',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2014-09-01',
            );
           
       
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '4',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '1');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '5');


    }

    public function testNegativoViradaDoAnoAgo()
    {

         $clientes[] = array(
            'ano' => '2014',
            'mes' => '8',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2014-09-01',
            );
           
       
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '1',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '1');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '5');


    }

    public function testNegativoViradaDoAnoAgoInt1()
    {

         $clientes[] = array(
            'ano' => '2014',
            'mes' => '11',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2014-09-01',
            );
           
       
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '1',
                'ano' => '2015',
                'int' => '1',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '1');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '2');


    }
    public function testNegativoViradaDoAnoDezInt1TemQueSerReg()
    {

         $clientes[] = array(
            'ano' => '2014',
            'mes' => '12',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2014-09-01',
            );
           
       
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '1',
                'ano' => '2015',
                'int' => '1',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        $this->assertEquals($clientes['resumo']['nReg'] , '1');
        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '');


    }

    public function testNovoComCompraSomenteNoMesAlvo()
    {

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
       
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;

        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '1');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '');


    }

    public function testNovoComCompraNoMesAlvoEOutraDeveSerRegular()
    {

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );

        $clientes[] = array(
            'ano' => '2013',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
       
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;

        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nReg'] , '1');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '');


    }

    public function testRecuperadoCompraNoMesAlvoEUmaNoLimite()
    {

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '5',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
       
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;

        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '1');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '');


    }

    public function testRecuperadoCompraNoMesAlvoEUmaAcimaLimiteDeveSerRegular()
    {

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '6',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
       
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;

        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nReg'] , '1');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '');


    }

    public function testRecuperadoCompraViradaDeAno()
    {

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '1',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
       
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '1',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;

        $this->assertEquals($clientes['resumo']['nPos'] , '');
        $this->assertEquals($clientes['resumo']['nReg'] , '');
        $this->assertEquals($clientes['resumo']['nNeg'] , '');
        $this->assertEquals($clientes['resumo']['nNov'] , '');
        $this->assertEquals($clientes['resumo']['nRec'] , '1');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '');


    }

    public function testMix1DeCada()
    {

        //Recuperado
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '4',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );

        $clientes[] = array(
            'ano' => '2015',
            'mes' => '5',
            'idProduto' => '4',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
       //Positivo
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '8',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '7',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '6',
            'idProduto' => '1',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        //Negativo
         $clientes[] = array(
            'ano' => '2015',
            'mes' => '5',
            'idProduto' => '2',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
         //Novo
         $clientes[] = array(
            'ano' => '2015',
            'mes' => '9',
            'idProduto' => '3',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
         //regular
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '8',
            'idProduto' => '5',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        /*
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '7',
            'idProduto' => '5',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );*/
        $clientes[] = array(
            'ano' => '2015',
            'mes' => '6',
            'idProduto' => '5',
            'nomeProduto' => 'Cliente de Teste',
            'qtdTotal' => '1',
            'valorTotal' => '2',
            'ultimaData' => '2015-08-01',
            );
        $cliStatus = new \Core\Utils\ProdutosStatusGenerator();
        $cliStatus->setParams(array(
                'mes' => '9',
                'ano' => '2015',
                'int' => '3',
            ));
        
        $clientes =  $cliStatus->generate($clientes);
        //var_dump($clientes);die;

        $this->assertEquals($clientes['resumo']['nPos'] , '1');
        $this->assertEquals($clientes['resumo']['nReg'] , '1');
        $this->assertEquals($clientes['resumo']['nNeg'] , '1');
        $this->assertEquals($clientes['resumo']['nNov'] , '1');
        $this->assertEquals($clientes['resumo']['nRec'] , '1');

        $this->assertEquals($clientes['itens']['1']['idStatus'] , '1');
        $this->assertEquals($clientes['itens']['1']['periodoStatus'] , '3');

        $this->assertEquals($clientes['itens']['2']['idStatus'] , '2');
        $this->assertEquals($clientes['itens']['2']['periodoStatus'] , '4');

        $this->assertEquals($clientes['itens']['3']['idStatus'] , '3');
        $this->assertEquals($clientes['itens']['3']['periodoStatus'] , '');

        $this->assertEquals($clientes['itens']['4']['idStatus'] , '4');
        $this->assertEquals($clientes['itens']['4']['periodoStatus'] , '');

        $this->assertEquals($clientes['itens']['5']['idStatus'] , '5');
        $this->assertEquals($clientes['itens']['5']['periodoStatus'] , '');



    }

} 


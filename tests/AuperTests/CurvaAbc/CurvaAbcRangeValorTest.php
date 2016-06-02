<?Php

namespace AuperTests\CurvaAbc;



class CurvaAbcRangeValorTest extends \PHPUnit_Framework_TestCase
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

    public function fillWithRest($rest, $instance)
    {
        for ($x=1;$x<=$rest;$x++) {
         $instance->addLinha(
            $nomeProduto = 'Produto X'.$x,
            $qtdTotal = '1',
            $valorUnit = '1',
            array()
            );
         }
         return $instance;
    }

    public function testDeBordaNoLimiteClasseAValor()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');
        //$this->assertInstanceOf('\Core\Permission\Model\PermissoesModel', $instance);

        $instance->addLinha(
            $nomeProduto = 'Produto 1',
            $qtdTotal = '1',
            $valorUnit = '65',
            array()
            );
        //QtdAcumulada = 65

        $instance->addLinha(
            $nomeProduto = 'Produto 2',
            $qtdTotal = '1',
            $valorUnit = '2',
            array()
            );
        //QtdAcumulada = 67

        $instance = $this->fillWithRest('33', $instance) ;    
        //QtdAcumulada = 100

        $table = $instance->getTable();

        //var_dump($table);die;
        $class = $instance->getClassForItem('Produto 1');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto 2');
        $this->assertEquals($class , 'B');


    }

    public function testDeBordaAbaixoDoLimiteClasseAValor()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');
        //$this->assertInstanceOf('\Core\Permission\Model\PermissoesModel', $instance);

        $instance->addLinha(
            $nomeProduto = 'Produto 1',
            $qtdTotal = '1',
            $valorUnit = '64',
            array()
            );
        //QtdAcumulada = 64

        $instance->addLinha(
            $nomeProduto = 'Produto 2',
            $qtdTotal = '1',
            $valorUnit = '2',
            array()
            );
        //QtdAcumulada = 66
        
        $instance = $this->fillWithRest('34', $instance) ;
        //QtdAcumulada = 100

        $table = $instance->getTable();

        $class = $instance->getClassForItem('Produto 1');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto 2');
        $this->assertEquals($class , 'A');

    }

    public function testDeBordaAcimaDoLimiteClasseAValor()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');
        //$this->assertInstanceOf('\Core\Permission\Model\PermissoesModel', $instance);

        $instance->addLinha(
            $nomeProduto = 'Produto 1',
            $qtdTotal = '1',
            $valorUnit = '66',
            array()
            );
        //QtdAcumulada = 66

        $instance->addLinha(
            $nomeProduto = 'Produto 2',
            $qtdTotal = '1',
            $valorUnit = '2',
            array()
            );
        //QtdAcumulada = 68

        $instance = $this->fillWithRest('32', $instance);
        //QtdAcumulada = 100

        $table = $instance->getTable();

        $class = $instance->getClassForItem('Produto 1');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto 2');
        $this->assertEquals($class , 'B');


    }

 
    public function testDeBordaNoLimiteClasseBValor()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');

        $instance->addLinha(
            $nomeProduto = 'Produto A',
            $qtdTotal = '1',
            $valorUnit = '65',
            array()
            );
        //QtdAcumulada = 65

        for($x=1;$x<=4;$x++){
            $instance->addLinha(
                $nomeProduto = 'Produto B'.$x,
                $qtdTotal = '1',
                $valorUnit = '5',
            array()
                );
        }
        //QtdAcumulada = 85


        $instance->addLinha(
                $nomeProduto = 'Produto B Alvo',
                $qtdTotal = '1',
                $valorUnit = '5',
            array()
                );
        //QtdAcumulada = 90

        $instance->addLinha(
                $nomeProduto = 'Produto C Alvo',
                $qtdTotal = '1',
                $valorUnit = '2',
            array()
                );
        //QtdAcumulada = 92


        $instance = $this->fillWithRest('8', $instance) ;
        //QtdAcumulada = 100

        $table = $instance->getTable();

        $class = $instance->getClassForItem('Produto A');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto B Alvo');
        $this->assertEquals($class , 'B');

        $class = $instance->getClassForItem('Produto C Alvo');
        $this->assertEquals($class , 'C');

    }

    public function testDeBordaAcimaDoLimiteClasseBValor()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');

        $instance->addLinha(
            $nomeProduto = 'Produto A',
            $qtdTotal = '1',
            $valorUnit = '65',
            array()
            );
        //QtdAcumulada = 65

        for($x=1;$x<=4;$x++){
            $instance->addLinha(
                $nomeProduto = 'Produto B'.$x,
                $qtdTotal = '1',
                $valorUnit = '5',
            array()
                );
        }
        //QtdAcumulada = 85

        $instance->addLinha(
                $nomeProduto = 'Produto B Alvo',
                $qtdTotal = '1',
                $valorUnit = '6',
            array()
                );
        //QtdAcumulada = 91

        $instance->addLinha(
                $nomeProduto = 'Produto C Alvo',
                $qtdTotal = '1',
                $valorUnit = '2',
            array()
                );
        //QtdAcumulada = 93

        $instance = $this->fillWithRest('7', $instance) ;
        //QtdAcumulada = 100

        $table = $instance->getTable();

        $class = $instance->getClassForItem('Produto A');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto B Alvo');
        $this->assertEquals($class , 'B');

        $class = $instance->getClassForItem('Produto C Alvo');
        $this->assertEquals($class , 'C');
        
    }

    public function testDeBordaAbaixoDoLimiteClasseBValor()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');

        $instance->addLinha(
            $nomeProduto = 'Produto A',
            $qtdTotal = '1',
            $valorUnit = '65',
            array()
            );
        //QtdAcumulada = 65

        for($x=1;$x<=4;$x++){
            $instance->addLinha(
                $nomeProduto = 'Produto B'.$x,
                $qtdTotal = '1',
                $valorUnit = '5',
            array()
                );
        }
        //QtdAcumulada = 85

        $instance->addLinha(
                $nomeProduto = 'Produto B Alvo',
                $qtdTotal = '1',
                $valorUnit = '4',
            array()
                );
        //QtdAcumulada = 89

        $instance->addLinha(
                $nomeProduto = 'Produto C Alvo',
                $qtdTotal = '1',
                $valorUnit = '2',
            array()
                );
        //QtdAcumulada = 91

        $instance = $this->fillWithRest('9', $instance) ;
        //QtdAcumulada = 100

        $table = $instance->getTable();

        $class = $instance->getClassForItem('Produto A');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto B Alvo');
        $this->assertEquals($class , 'B');

        $class = $instance->getClassForItem('Produto C Alvo');
        $this->assertEquals($class , 'B');
        
    }
/*
    public function testNoRangeDoValor(){
        $instance = new \Core\Utils\CurvaAbc($asd = '');

        $instance->addLinha(
            $nomeProduto = 'P1',
            $qtdTotal = '30',
            $valorUnit = '71'
            );

         $instance->addLinha(
            $nomeProduto = 'P2',
            $qtdTotal = '20',
            $valorUnit = '20'
            );

          $instance->addLinha(
            $nomeProduto = 'P3',
            $qtdTotal = '50',
            $valorUnit = '9'
            );

          $table = $instance->getTable();

        $class = $instance->getClassForItem('P1');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('P2');
        $this->assertEquals($class , 'B');

        $class = $instance->getClassForItem('P3');
        $this->assertEquals($class , 'C');
    }
  */
 

} 


<?Php

namespace AuperTests\CurvaAbc;

use Core\Utils\CurvaAbc;
class CurvaAbcRangeQuantidadeTest extends \PHPUnit_Framework_TestCase
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
/*
    public function testComTresItens()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');
        //$this->assertInstanceOf('\Core\Permission\Model\PermissoesModel', $instance);

        $instance->addLinha(
            $nomeProduto = 'Produto 1',
            $qtdTotal = '15',
            $valorUnit = '1'
            );

        $instance->addLinha(
            $nomeProduto = 'Produto 2',
            $qtdTotal = '6',
            $valorUnit = '1'
            );

        $instance->addLinha(
            $nomeProduto = 'Produto 3',
            $qtdTotal = '2',
            $valorUnit = '1'
            );

        for($x=1;$x<76;$x++){
         $instance->addLinha(
            $nomeProduto = 'Produto X'.$x,
            $qtdTotal = '1',
            $valorUnit = '1'
            );
         }
         
        

        $table = $instance->getTable();

       // var_dump($table);die;
       $this->assertEquals($table[0]['item'] , 'Produto 1');
       $this->assertEquals($table[0]['classe'] , 'A');
       $this->assertEquals($table[1]['item'] , 'Produto 2');
       $this->assertEquals($table[1]['classe'] , 'A');
       $this->assertEquals($table[2]['item'] , 'Produto 3');
       $this->assertEquals($table[2]['classe'] , 'B');
       //$this->assertEquals($table[0]['classe'] , 'A');
    }
*/
    public function fillWithRest($rest, $instance)
    {
        for ($x=1;$x<=$rest;$x++) {
         $instance->addLinha(
            $nomeProduto = 'Produto X'.$x,
            $qtdTotal = '1',
            $valorUnit = '1'
            );
         }
         return $instance;
    }

    public function checaProdutosC($table)
    {
        for ($x=1;$x<=100;$x++) {
            foreach ($table as $key => $item) {
                if($item['item'] == 'Produto X'.$x){
                    $this->assertEquals($item['classe'] , 'C');
                }
            }
        }
    }
    public function testDeBordaNoLimiteClasseAQtd()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');
        //$this->assertInstanceOf('\Core\Permission\Model\PermissoesModel', $instance);

        $instance->addLinha(
            $nomeProduto = 'Produto 1',
            $qtdTotal = '20',
            $valorUnit = '1'
            );
        //QtdAcumulada = 20

        $instance->addLinha(
            $nomeProduto = 'Produto 2',
            $qtdTotal = '2',
            $valorUnit = '1'
            );
        //QtdAcumulada = 22

        $instance = $this->fillWithRest('78', $instance) ;    
        //QtdAcumulada = 100

        $table = $instance->getTable();

        //var_dump($table);die;
        $class = $instance->getClassForItem('Produto 1');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto 2');
        $this->assertEquals($class , 'B');


    }

    public function testDeBordaAbaixoDoLimiteClasseAQtd()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');
        //$this->assertInstanceOf('\Core\Permission\Model\PermissoesModel', $instance);

        $instance->addLinha(
            $nomeProduto = 'Produto 1',
            $qtdTotal = '19',
            $valorUnit = '1'
            );
        //QtdAcumulada = 19

        $instance->addLinha(
            $nomeProduto = 'Produto 2',
            $qtdTotal = '2',
            $valorUnit = '1'
            );
        //QtdAcumulada = 21
        
        $instance = $this->fillWithRest('79', $instance) ;
        //QtdAcumulada = 100

        $table = $instance->getTable();

        $class = $instance->getClassForItem('Produto 1');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto 2');
        $this->assertEquals($class , 'A');

    }

    public function testDeBordaAcimaDoLimiteClasseAQtd()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');
        //$this->assertInstanceOf('\Core\Permission\Model\PermissoesModel', $instance);

        $instance->addLinha(
            $nomeProduto = 'Produto 1',
            $qtdTotal = '21',
            $valorUnit = '1'
            );
        //QtdAcumulada = 21

        $instance->addLinha(
            $nomeProduto = 'Produto 2',
            $qtdTotal = '2',
            $valorUnit = '1'
            );
        //QtdAcumulada = 23

        $instance = $this->fillWithRest('77', $instance);
        //QtdAcumulada = 19

        $table = $instance->getTable();

        $class = $instance->getClassForItem('Produto 1');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto 2');
        $this->assertEquals($class , 'B');


    }

 
    public function testDeBordaNoLimiteClasseBQtd()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');

        $instance->addLinha(
            $nomeProduto = 'Produto A',
            $qtdTotal = '20',
            $valorUnit = '1'
            );
        //QtdAcumulada = 20

        for($x=1;$x<=5;$x++){
            $instance->addLinha(
                $nomeProduto = 'Produto B'.$x,
                $qtdTotal = '5',
                $valorUnit = '1'
                );
        }
        //QtdAcumulada = 45


        $instance->addLinha(
                $nomeProduto = 'Produto B Alvo',
                $qtdTotal = '5',
                $valorUnit = '1'
                );
        //QtdAcumulada = 60

        $instance->addLinha(
                $nomeProduto = 'Produto C Alvo',
                $qtdTotal = '2',
                $valorUnit = '1'
                );
        //QtdAcumulada = 62


        $instance = $this->fillWithRest('38', $instance) ;
        //QtdAcumulada = 100

        $table = $instance->getTable();

        $class = $instance->getClassForItem('Produto A');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto B Alvo');
        $this->assertEquals($class , 'B');

        $class = $instance->getClassForItem('Produto C Alvo');
        $this->assertEquals($class , 'C');

    }

    public function testDeBordaAcimaDoLimiteClasseBQtd()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');

        $instance->addLinha(
            $nomeProduto = 'Produto A',
            $qtdTotal = '20',
            $valorUnit = '1'
            );
        //QtdAcumulada = 20

        for($x=1;$x<=5;$x++){
            $instance->addLinha(
                $nomeProduto = 'Produto B'.$x,
                $qtdTotal = '5',
                $valorUnit = '1'
                );
        }
        //QtdAcumulada = 45

        $instance->addLinha(
                $nomeProduto = 'Produto B Alvo',
                $qtdTotal = '6',
                $valorUnit = '1'
                );
        //QtdAcumulada = 51

        $instance->addLinha(
                $nomeProduto = 'Produto C Alvo',
                $qtdTotal = '2',
                $valorUnit = '1'
                );
        //QtdAcumulada = 53

        $instance = $this->fillWithRest('47', $instance) ;
        //QtdAcumulada = 100

        $table = $instance->getTable();

        $class = $instance->getClassForItem('Produto A');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto B Alvo');
        $this->assertEquals($class , 'B');

        $class = $instance->getClassForItem('Produto C Alvo');
        $this->assertEquals($class , 'C');
        
    }

    public function testDeBordaAbaixoDoLimiteClasseBQtd()
    {
        $instance = new \Core\Utils\CurvaAbc($asd = '');

        $instance->addLinha(
            $nomeProduto = 'Produto A',
            $qtdTotal = '20',
            $valorUnit = '1'
            );
        //QtdAcumulada = 20

        for($x=1;$x<=5;$x++){
            $instance->addLinha(
                $nomeProduto = 'Produto B'.$x,
                $qtdTotal = '5',
                $valorUnit = '1'
                );
        }
        //QtdAcumulada = 45

        $instance->addLinha(
                $nomeProduto = 'Produto B Alvo',
                $qtdTotal = '4',
                $valorUnit = '1'
                );
        //QtdAcumulada = 49

        $instance->addLinha(
                $nomeProduto = 'Produto C Alvo',
                $qtdTotal = '2',
                $valorUnit = '1'
                );
        //QtdAcumulada = 51

        $instance = $this->fillWithRest('49', $instance) ;
        //QtdAcumulada = 100

        $table = $instance->getTable();

        $class = $instance->getClassForItem('Produto A');
        $this->assertEquals($class , 'A');

        $class = $instance->getClassForItem('Produto B Alvo');
        $this->assertEquals($class , 'B');

        $class = $instance->getClassForItem('Produto C Alvo');
        $this->assertEquals($class , 'B');
        
    }


    public function testNoRangeDoValor(){
        $instance = new \Core\Utils\CurvaAbc($asd = '');

        $instance->addLinha(
            $nomeProduto = 'P1',
            $qtdTotal = '20',
            $valorUnit = '71'
            );

         $instance->addLinha(
            $nomeProduto = 'P2',
            $qtdTotal = '71',
            $valorUnit = '20'
            );

          $instance->addLinha(
            $nomeProduto = 'P3',
            $qtdTotal = '9',
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
  
 

} 


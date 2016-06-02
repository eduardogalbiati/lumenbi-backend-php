<?Php

namespace AuperTests\CurvaAbc;



class DateOperationTest extends \PHPUnit_Framework_TestCase
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
                class_exists($class = '\Core\Utils\DateOperation'),
                'Class not found: '.$class
        );

        $this->assertInstanceOf('\Silex\Application', $this->app);
        
    }



    public function testeDezembroSomandoMesProximoAno()
    {
       $date = new \Datetime('2015-12-01');
       $inst = new \Core\Utils\DateOperation($date);

       $inst->addMonth('1');

       $this->assertEquals($inst->getMonth() , '1');
       $this->assertEquals($inst->getYear() , '2016');

    }

    public function testeJaneiroSubtraindoMes()
    {
       $date = new \Datetime('2015-01-01');
       $inst = new \Core\Utils\DateOperation($date);

       $inst->subMonth('1');

       $this->assertEquals($inst->getMonth() , '12');
       $this->assertEquals($inst->getYear() , '2014');

    }


    public function testeSomando24Meses()
    {
       $date = new \Datetime('2015-01-01');
       $inst = new \Core\Utils\DateOperation($date);

       $inst->addMonth('24');

       $this->assertEquals($inst->getMonth() , '01');
       $this->assertEquals($inst->getYear() , '2017');

    }

    public function testeSomando26Meses()
    {
       $date = new \Datetime('2015-01-01');
       $inst = new \Core\Utils\DateOperation($date);

       $inst->addMonth('26');

       $this->assertEquals($inst->getMonth() , '03');
       $this->assertEquals($inst->getYear() , '2017');

    }

    
 

} 


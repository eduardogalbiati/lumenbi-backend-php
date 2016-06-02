<?Php
namespace Auper\Clientes\Hydrator;

use Silex\Application;

use Auper\Clientes\DataMapper\ClientesDataMapper;

class ClientesStatusCurvaAbcHydrator
{
    protected $app;
    protected $newArray = array();
    protected $cdm;
    protected $clientes;

    protected $cliNovos;
    protected $cliPositivos;
    protected $cliNegativos;
    protected $cliRecuperados;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->cdm = new ClientesDataMapper($app['db']);
    }

    protected function fetchClientesNovos($month)
    {
        $novos =  $this->cdm->loadClientesNovos($month);
        foreach ($novos as $cliente) {
            $this->clientes[$cliente['cliente']] = '1';
        }

    }


    public function hydrate(array $array, $month = '9')
    {
        $this->fetchClientesNovos($month);

        foreach($array as $k => $cliente)
        {
            if($this->clientes[$cliente['item']]){
                $array[$k]['status'] = $this->clientes[$cliente['item']];
            }else{
                $array[$k]['status'] = '';
            }

        }
        return $array;

    }

    

}

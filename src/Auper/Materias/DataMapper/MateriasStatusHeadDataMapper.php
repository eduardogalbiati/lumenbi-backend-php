<?Php
namespace Auper\Clientes\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DateOperation;


class ClientesStatusHeadDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

     public function insert(array $clientes)
    {

        //$this->dropTable("Auper.dbo.clientesStatus");
        foreach ($clientes as $line) {
            $this->insertTableArray('Auper.dbo.clientesStatusHead', $line);
        }

    }




 public function loadClientesStatusHead($mesAlvo, $anoAlvo, $intervalo)
    {

        $date = new \DateTime($anoAlvo.'-12-01');
        $dateOp = new DateOperation($date);
        $mes = $dateOp->getMonth();
        $ano = $dateOp->getYear();

        for($i=0;$ano == $anoAlvo;$i++){


            if($i==0){
                $query .= "((ANO='".$ano."' AND MES='".$mes."')";
            }
            $query .= " OR (ANO='".$ano."' AND MES='".$mes."')";

            $dateOp->subMonth(1);
              
            $mes = $dateOp->getMonth();
            $ano = $dateOp->getYear();
        
        }
        $query .= ') AND intervalo='.$intervalo;

        $stmt = $this->db->prepare(" SELECT
            mes,
            ano,
            intervalo,
            rec,
          [pos]
          ,[nov]
          ,[neg]
          ,[total]
          

  FROM [Auper].[dbo].[clientesStatusHead]
            Where ".$query." ORDER BY ano asc,mes asc");

        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $k => $res) {
            $vPos[] = array($res['mes'], $res['pos']);
            $vNeg[] = array($res['mes'], $res['neg']);
            $vRec[] = array($res['mes'], $res['rec']);
            $vNov[] = array($res['mes'], $res['nov']);
        }


        $ret = array(
            'itens' => array(
                'pos' => $vPos,
                'neg' => $vNeg,
                'rec' => $vRec,
                'nov' => $vNov,
                ),
            );
        
       return $ret;
    }
}
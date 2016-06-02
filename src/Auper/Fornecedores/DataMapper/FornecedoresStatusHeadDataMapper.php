<?Php
namespace Auper\Fornecedores\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DateOperation;


class FornecedoresStatusHeadDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

     public function insert(array $fornecedores)
    {

        //$this->dropTable("Auper.dbo.FornecedoresStatus");
        foreach ($fornecedores as $line) {
            $this->insertTableArray('Auper.dbo.fornecedoresStatusHead', $line);
        }

    }




public function loadfornecedoresStatusHead($mesAlvo, $anoAlvo, $intervalo)
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
          ,[reg]
          ,[total]
          

  FROM [Auper].[dbo].[fornecedoresStatusHead]
            Where ".$query." ORDER BY ano asc,mes asc");

        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $k => $res) {
            $vPos[] = array($res['mes'], $res['pos']);
            $vNeg[] = array($res['mes'], $res['neg']);
            $vRec[] = array($res['mes'], $res['rec']);
            $vNov[] = array($res['mes'], $res['nov']);
            $vReg[] = array($res['mes'], $res['reg']);
        }


        $ret = array(
            'itens' => array(
                'pos' => $vPos,
                'neg' => $vNeg,
                'rec' => $vRec,
                'nov' => $vNov,
                'reg' => $vReg,
                ),
            );
        
       return $ret;
    }


    public function loadFornecedoresStatusComparativoHead($mesAlvo, $anoAlvo, $intervalo)
    {


        $stmt = $this->db->prepare(" SELECT
            mes,
            ano,
            intervalo,
            rec,
           [pos]
          ,[nov]
          ,[neg]
          ,[reg]
          ,[total]
          
  FROM [Auper].[dbo].[fornecedoresStatusHead]
            Where ano = :ano AND mes = :mes AND intervalo= :intervalo ");

        $stmt->bindValue("ano",$anoAlvo);
        $stmt->bindValue("mes",$mesAlvo);
        $stmt->bindValue("intervalo",$intervalo);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $ret = array(
            'itens' => array(
                'pos' => $result[0]['pos'],
                'neg' => $result[0]['neg'],
                'rec' => $result[0]['rec'],
                'nov' => $result[0]['nov'],
                'reg' => $result[0]['reg'],
                'total' => $result[0]['total'],
                ),
            );
        
       return $ret;
    }
}
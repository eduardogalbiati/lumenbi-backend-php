<?Php
namespace Auper\CurvaAbc\ComprasProdutos\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class CurvaAbcComprasProdutosHeadDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function clearTable()
    {
        $this->dropTable("Auper.dbo.abcComprasProdutosHead");
    }

    public function insert(array $compras)
    {
        //$this->clearTable();
        foreach ($compras as $line) {
            $this->insertTableArray('Auper.dbo.abcComprasProdutosHead', $line);
        }

    }


    

    public function loadHead($mes, $ano, $int)
    {
        $date = new \DateTime($ano.'-'.$mes.'-01');
        //$date->sub(new \DateInterval('P1M'));
        //echo $date->format('m');;die;

        for($i=0;$i<12;$i++){

            $ano = $date->format('Y');
            $mes = $date->format('m');

            if($ano < date("Y"))
                break;

            if($i==0){
                $query .= "((ANO='".$ano."' AND MES='".$mes."')";
            }
            $query .= " OR (ANO='".$ano."' AND MES='".$mes."')";
            $date->sub(new \DateInterval('P1M'));
        }
        $query .= ') AND intervalo='.$int;


          $stmt = $this->db->prepare(" SELECT
            mes,
            ano,
            intervalo,
            classe,
          [qtdItens]
          ,[qtdItensDistintos]
          ,[valorTotal]
          

  FROM [Auper].[dbo].[AbcComprasProdutosHead]
            Where ".$query." ORDER BY ano asc,mes asc");

           $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $k => $res) {
            unset($head);
            $head['qtdItens'] = $res['qtdItens'];
            $head['qtdItensDistintos'] = $res['qtdItensDistintos'];
            $head['valorTotal'] = $res['valorTotal'];
            $head['ano'] = $res['ano'];
            $head['mes'] = $res['mes'];
            $head['intervalo'] = $res['intervalo'];
            $arr['detalhe'][$res['classe']][] = $head;
            $arr['resumo'][$res['classe']]['qtdItensDistintos'][] = ($res['qtdItensDistintos']);
            $arr['resumo'][$res['classe']]['valorTotal'][] = array( $res['mes'] ,(float)$res['valorTotal']);
            $arr['resumo']['qtdItensDistintos'] +=  ($res['qtdItensDistintos']);

            $pizza[$res['classe']] += $res['valorTotal'];
            $valorTotal[$res['mes']] += $res['valorTotal'];
        }

        foreach($valorTotal as $mes => $valor){
            $arr['resumo']['valorTotal'][] = array($mes, $valor);
            $valorFinalTotal  += $valor;
        }

        //sort($pizza);
        foreach($pizza as $classe => $valor){
            $arr['resumo']['pizza']['chart'][] = $valor;
            $arr['resumo']['pizza']['info'][] = round($valor * 100 / $valorFinalTotal);
        }

       return $arr;
    }

    
}

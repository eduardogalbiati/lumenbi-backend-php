<?Php
namespace Auper\CurvaAbc\ComprasFornecedores\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class CurvaAbcComprasFornecedoresDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function clearTable()
    {
        $this->dropTable("Auper.dbo.abcComprasFornecedores");
    }

    public function insert(array $vendas)
    {
        //$this->clearTable();
        foreach ($vendas as $line) {
            $this->insertTableArray('Auper.dbo.abcComprasFornecedores', $line);
        }

    }


    public function loadHistoricoAntesDe($mes, $ano, $int)
    {
        $date = new \DateTime($ano.'-'.$mes.'-01');

        for($i=0;$i<12;$i++){
            $ano = $date->format('Y');
            $mes = $date->format('m');
            if($i==0){
                $query .= "((VC.ANO='".$ano."' AND VC.MES='".$mes."')";
            }
            $query .= " OR (VC.ANO='".$ano."' AND VC.MES='".$mes."')";
            $date->sub(new \DateInterval('P1M'));
        }
        $query .= ') AND VC.intervalo='.$int;

          $sql = " SELECT
           VC.mes
          ,VC.ano
          ,VC.intervalo
          ,VC.[class]
          ,VC.[qtd]
          ,VC.[valor]
          ,VC.[prcQtd]
          ,VC.[prcValor]
          ,VC.[pos]
          ,P.idFornecedor 
          ,P.nomeFornecedor

  FROM [Auper].[dbo].[AbcComprasFornecedores] VC INNER JOIN [Auper].[dbo].[fornecedores] P ON P.idFornecedor = VC.idFornecedor 
            Where ".$query." ORDER BY VC.ano asc, VC.mes asc";
          $stmt = $this->db->prepare($sql);


           $stmt->execute();
        $result = $stmt->fetchAll();
        $arr = array();
        foreach ($result as $k => $res) {
            unset($cliente);
            $cliente['prcQtd'] = $res['prcQtd'];
            $cliente['prcValor'] = $res['prcValor'];
            $cliente['class'] = $res['class'];
            $cliente['valor'] = $res['valor'];
            $cliente['ano'] = $res['ano'];
            $cliente['mes'] = $res['mes'];
            $cliente['pos'] = $res['pos'];
            $cliente['int'] = $res['intervalo'];
            
            $arr[$res['nomeFornecedor']]['detalhe'][] = $cliente;
            $arr[$res['nomeFornecedor']]['resumo'][] = ($res['pos']*-1);
        }

       return $arr;
    }

    
    public function loadClasses($mes, $ano, $int)
    {
       
        $sql = " SELECT
         VC.mes
        ,VC.ano
        ,VC.intervalo
        ,VC.[class]
        ,VC.[qtd]
        ,VC.[valor]
        ,VC.[prcQtd]
        ,VC.[prcValor]
        ,VC.[pos]
        ,C.idFornecedor 
        ,C.nomeFornecedor

FROM [Auper].[dbo].[AbcComprasFornecedores] VC INNER JOIN [Auper].[dbo].[Fornecedores] C ON C.idFornecedor = VC.idFornecedor 
          Where 
          VC.intervalo = :intervalo
          AND VC.ano = :ano
          AND VC.mes = :mes

           ORDER BY VC.ano asc, VC.mes asc";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue("intervalo", $int);
        $stmt->bindValue("ano", $ano);
        $stmt->bindValue("mes", $mes);
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach($result as $tupla){
          $arr[$tupla['idFornecedor']] = $tupla;
        }

       return $arr;
    }

    public function loadClasseHistorico($idFornecedor, $intervalo)
    {
       

          $sql = " SELECT
           VC.mes
          ,VC.ano
          ,VC.[class]
          ,VC.[pos]
  FROM [Auper].[dbo].[AbcComprasfornecedores] VC INNER JOIN [Auper].[dbo].[fornecedores] P ON P.idFornecedor = VC.idFornecedor 
            Where VC.idFornecedor = :idFornecedor  AND VC.intervalo = :intervalo ORDER BY VC.ano desc, VC.mes desc";
          $stmt = $this->db->prepare($sql);

      $stmt->bindValue("idFornecedor",$idFornecedor);
      $stmt->bindValue("intervalo",$intervalo);

           $stmt->execute();
        $result = $stmt->fetchAll();
       
       return $result;
    }
    
}

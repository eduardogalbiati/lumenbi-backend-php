<?Php
namespace Auper\CurvaAbc\VendasVendedores\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class CurvaAbcVendasVendedoresDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function clearTable()
    {
        $this->dropTable("Auper.dbo.AbcVendasVendedores");
    }

    public function insert(array $vendas)
    {
        //$this->clearTable();
        foreach ($vendas as $line) {
            $this->insertTableArray('Auper.dbo.AbcVendasVendedores', $line);
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
          ,C.idVendedor 
          ,C.nomeVendedor

  FROM [Auper].[dbo].[AbcVendasVendedores] VC INNER JOIN [Auper].[dbo].[vendedores] C ON C.idVendedor = VC.idVendedor 
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
            
            $arr[$res['nomeVendedor']]['detalhe'][] = $cliente;
            $arr[$res['nomeVendedor']]['resumo'][] = ($res['pos']*-1);
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
        ,C.idVendedor 
        ,C.nomeVendedor

FROM [Auper].[dbo].[AbcVendasVendedores] VC INNER JOIN [Auper].[dbo].[vendedores] C ON C.idVendedor = VC.idVendedor 
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
          $arr[$tupla['idVendedor']] = $tupla;
        }

       return $arr;
    }

     public function loadClasseHistorico($idVendedor, $intervalo)
    {
       

          $sql = " SELECT
           VC.mes
          ,VC.ano
          ,VC.[class]
          ,VC.[pos]
  FROM [Auper].[dbo].[AbcVendasVendedores] VC INNER JOIN [Auper].[dbo].[vendedores] P ON P.idVendedor = VC.idVendedor 
            Where VC.idVendedor = :idVendedor  AND VC.intervalo = :intervalo ORDER BY VC.ano desc, VC.mes desc";
          $stmt = $this->db->prepare($sql);

      $stmt->bindValue("idVendedor",$idVendedor);
      $stmt->bindValue("intervalo",$intervalo);

           $stmt->execute();
        $result = $stmt->fetchAll();
       
       return $result;
    }

    
}

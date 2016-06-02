<?Php
namespace Auper\Fornecedores\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DateOperation;


class FornecedoresDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

     public function insert(array $fornecedores)
    {

        $this->dropTable("Auper.dbo.fornecedores");
        foreach ($fornecedores as $line) {
            $this->insertTableArray('Auper.dbo.fornecedores', $line);
        }

    }

    public function update($fornecedores){

        foreach($fornecedores as $fornecedor){
          $id = $fornecedor['idFornecedor'];
          unset($fornecedor['idFornecedor']);
          $this->updateTableById('Auper.dbo.fornecedores', $fornecedor , array('idFornecedor' => $id));
        }
    }


    public function loadTodos($dataInicio, $dataFim, $ativo)
    {
      
      $stmt = $this->db->prepare("SELECT 
          [idFornecedor], 
          [nomeFornecedor], 
          [telefone1], 
          [ativo], 
          [status]
              
        FROM   [Auper].[dbo].[fornecedores] 
        WHERE dataHoraCad >= :dataInicio AND dataHoraCad <= :dataFim AND ativo=:ativo
        Order by nomeFornecedor asc
");

      $stmt->bindValue("dataInicio",$dataInicio);
      $stmt->bindValue("dataFim",$dataFim);
      $stmt->bindValue("ativo",$ativo);

      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores;


    }

    public function getCountFornecedores($ativo)
    {
      
      $stmt = $this->db->prepare("SELECT 
        COUNT(1)  as QTD   
        FROM   [Auper].[dbo].[fornecedores]");

      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores[0]['QTD'];
      return $fornecedores;


    }

    public function loadFornecedoresForStatus($mesAlvo, $anoAlvo, $intervalo)
    {

        $ultimoDiaMesAlvo = date("t", mktime(0,0,0,$mesAlvo,'01',$anoAlvo));

        $date = new \DateTime($anoAlvo.'-'.$mesAlvo.'-01');
        $dateOp = new DateOperation($date);
        
       // $dateOp->subMonth(1);
        $dateOp->subMonth($intervalo);
        $mesInt = $dateOp->getMonth();
        $anoInt = $dateOp->getYear();


      $sql = "SELECT 
                VC.[idFornecedor], 
                C.nomeFornecedor,
                Sum(VC.qtd) AS qtdTotal, 
                Sum(VC.valor) AS valorTotal,
                Max(VC.dataHora) as ultimaData,
                VC.ano,
                VC.mes
            FROM   [Auper].[dbo].[comprasqtdvaloresfornecedores]  VC
            INNER JOIN  [Auper].[dbo].[fornecedores] C 
            ON C.idFornecedor = VC.idFornecedor
            
            GROUP  BY VC.idFornecedor,
                      C.nomeFornecedor,
                      VC.ano,
                      VC.mes
                      ORDER BY VC.idFornecedor, ano desc, mes desc";

      $stmt = $this->db->prepare($sql);

      $stmt->execute();

      $fornecedores = $stmt->fetchAll();
      return $fornecedores;

    }


    public function loadComprasForFornecedor($idFornecedor, $ano)
    {

        $sql = "SELECT 
                VC.[idFornecedor], 
                C.nomeFornecedor,
                Sum(VC.qtd) AS qtdTotal, 
                Sum(VC.valor) AS valorTotal,
                Max(VC.dataHora) as ultimaData,
                VC.ano,
                VC.mes
            FROM   [Auper].[dbo].[comprasqtdvaloresfornecedores]  VC
            INNER JOIN  [Auper].[dbo].[fornecedores] C 
            ON C.idFornecedor = VC.idFornecedor
            WHERE VC.idFornecedor = :idFornecedor
               AND VC.ano = :ano
            GROUP  BY VC.idFornecedor,
                      C.nomeFornecedor,
                      VC.ano,
                      VC.mes
                      ORDER BY VC.idFornecedor, ano desc, mes desc";

      $stmt = $this->db->prepare($sql);

      $stmt->bindValue("idFornecedor",$idFornecedor);
      $stmt->bindValue("ano",$ano);

      $stmt->execute();

      $fornecedores = $stmt->fetchAll();
      return $fornecedores;
    }


    public function loadById($idFornecedor)
    {
      
      $stmt = $this->db->prepare("SELECT 
       *  
        FROM   [Auper].[dbo].[fornecedores] 
        WHERE idFornecedor = :idFornecedor");

      $stmt->bindValue("idFornecedor",$idFornecedor);

      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores[0];

    }
    
}

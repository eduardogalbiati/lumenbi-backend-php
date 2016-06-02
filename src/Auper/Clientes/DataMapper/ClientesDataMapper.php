<?Php
namespace Auper\Clientes\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DateOperation;

class ClientesDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

     public function insert(array $clientes)
    {

        $this->dropTable("Auper.dbo.clientes");
        foreach ($clientes as $line) {
            $this->insertTableArray('Auper.dbo.clientes', $line);
        }

    }

    public function update($clientes){

        foreach($clientes as $cliente){
          $id = $cliente['idCliente'];
          unset($cliente['idCliente']);
          $this->updateTableById('Auper.dbo.clientes', $cliente , array('idCliente' => $id));
        }
    }


    public function loadTodos($dataInicio, $dataFim, $ativo)
    {
      
      $stmt = $this->db->prepare("SELECT 
          [idCliente], 
          [nomeCliente], 
          [telefone1], 
          [ativo], 
          [status]
              
        FROM   [Auper].[dbo].[clientes] 
        WHERE dataHoraCad >= :dataInicio AND dataHoraCad <= :dataFim AND ativo=:ativo
        Order by nomeCliente asc
");

      $stmt->bindValue("dataInicio",$dataInicio);
      $stmt->bindValue("dataFim",$dataFim);
      $stmt->bindValue("ativo",$ativo);

      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes;


    }

    public function getCountClientes($ativo)
    {
      
      $stmt = $this->db->prepare("SELECT 
        COUNT(1)  as QTD   
        FROM   [Auper].[dbo].[clientes] 
        WHERE ativo = :ativo");

      $stmt->bindValue("ativo",$ativo);

      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes[0]['QTD'];
      return $clientes;


    }

    public function dropClientes()
    {
      
      
      $stmt = $this->db->prepare("DELETE 
        
        FROM   [Auper].[dbo].[clientes] 
       ");

     
     
      $stmt->execute();

      return true;

    }

    public function loadClientesForStatus($mesAlvo, $anoAlvo, $intervalo)
    {

        $ultimoDiaMesAlvo = date("t", mktime(0,0,0,$mesAlvo,'01',$anoAlvo));

        $date = new \DateTime($anoAlvo.'-'.$mesAlvo.'-01');
        $dateOp = new DateOperation($date);
        
       // $dateOp->subMonth(1);
        $dateOp->subMonth($intervalo);
        $mesInt = $dateOp->getMonth();
        $anoInt = $dateOp->getYear();


      $sql = "SELECT 
                VC.[idCliente], 
                C.nomeCliente,
                Sum(VC.qtd) AS qtdTotal, 
                Sum(VC.valor) AS valorTotal,
                Max(VC.dataHora) as ultimaData,
                VC.ano,
                VC.mes
            FROM   [Auper].[dbo].[vendasqtdvaloresclientes]  VC
            INNER JOIN  [Auper].[dbo].[clientes] C 
            ON C.idCliente = VC.idCliente
            
            GROUP  BY VC.idCliente,
                      C.nomeCliente,
                      VC.ano,
                      VC.mes
                      ORDER BY VC.idCliente, ano desc, mes desc";

      $stmt = $this->db->prepare($sql);

      $stmt->execute();

      $clientes = $stmt->fetchAll();
      return $clientes;

    }

    public function loadVendasForCliente($idCliente, $ano)
    {

        $sql = "SELECT 
                VC.[idCliente], 
                C.nomeCliente,
                Sum(VC.qtd) AS qtdTotal, 
                Sum(VC.valor) AS valorTotal,
                Max(VC.dataHora) as ultimaData,
                VC.ano,
                VC.mes
            FROM   [Auper].[dbo].[vendasqtdvaloresClientes]  VC
            INNER JOIN  [Auper].[dbo].[Clientes] C 
            ON C.idCliente = VC.idCliente
            WHERE VC.idCliente = :idCliente
               AND VC.ano = :ano
            GROUP  BY VC.idCliente,
                      C.nomeCliente,
                      VC.ano,
                      VC.mes
                      ORDER BY VC.idCliente, ano desc, mes desc";

      $stmt = $this->db->prepare($sql);

      $stmt->bindValue("idCliente",$idCliente);
      $stmt->bindValue("ano",$ano);

      $stmt->execute();

      $clientes = $stmt->fetchAll();
      return $clientes;
    }


    public function loadById($idCliente)
    {
      
      $stmt = $this->db->prepare("SELECT 
       *  
        FROM   [Auper].[dbo].[clientes] 
        WHERE idCliente = :idCliente");

      $stmt->bindValue("idCliente",$idCliente);

      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes[0];

    }


    
}

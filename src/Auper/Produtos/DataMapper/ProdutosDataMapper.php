<?Php
namespace Auper\Produtos\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DateOperation;

class ProdutosDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

   public function insert(array $produtos)
    {

        $this->dropTable("Auper.dbo.produtos");
        foreach ($produtos as $line) {
            $this->insertTableArray('Auper.dbo.produtos', $line);
        }

    }

    public function update($produtos){

        foreach($produtos as $produto){
          $id = $produto['idProduto'];
          unset($produto['idProduto']);
          $this->updateTableById('Auper.dbo.produtos', $produto , array('idProduto' => $id));
        }
    }

    public function loadTodos($dataInicio, $dataFim, $ativo)
    {
      
      $stmt = $this->db->prepare("SELECT 
          [idProduto], 
          [nomeProduto], 
          [telefone1], 
          [ativo], 
          [status]
              
        FROM   [Auper].[dbo].[produtos] 
        WHERE dataHoraCad >= :dataInicio AND dataHoraCad <= :dataFim AND ativo=:ativo
        Order by nomeProduto asc
");

      $stmt->bindValue("dataInicio",$dataInicio);
      $stmt->bindValue("dataFim",$dataFim);
      $stmt->bindValue("ativo",$ativo);

      $stmt->execute();

      if (!$produtos = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $produtos;


    }

    public function getCountProdutos($ativo)
    {
      
      $stmt = $this->db->prepare("SELECT 
        COUNT(1)  as QTD   
        FROM   [Auper].[dbo].[produtos] 
        WHERE ativo = :ativo");

      $stmt->bindValue("ativo",$ativo);

      $stmt->execute();

      if (!$produtos = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $produtos[0]['QTD'];
      return $produtos;


    }

    public function loadProdutosForStatus($mesAlvo, $anoAlvo, $intervalo)
    {

        $ultimoDiaMesAlvo = date("t", mktime(0,0,0,$mesAlvo,'01',$anoAlvo));

        $date = new \DateTime($anoAlvo.'-'.$mesAlvo.'-01');
        $dateOp = new \Core\Utils\DateOperation($date);
        
       // $dateOp->subMonth(1);
        $dateOp->subMonth($intervalo);
        $mesInt = $dateOp->getMonth();
        $anoInt = $dateOp->getYear();


      $sql = "SELECT 
                VC.[idProduto], 
                C.nomeProduto,
                Sum(VC.qtd) AS qtdTotal, 
                Sum(VC.valor) AS valorTotal,
                Max(VC.dataHora) as ultimaData,
                VC.ano,
                VC.mes
            FROM   [Auper].[dbo].[vendasqtdvaloresprodutos]  VC
            INNER JOIN  [Auper].[dbo].[produtos] C 
            ON C.idProduto = VC.idProduto
            
            GROUP  BY VC.idProduto,
                      C.nomeProduto,
                      VC.ano,
                      VC.mes
                      ORDER BY VC.idProduto, ano desc, mes desc";

      $stmt = $this->db->prepare($sql);

      $stmt->execute();

      $produtos = $stmt->fetchAll();
      return $produtos;

    }


    public function loadVendasForProduto($idProduto, $ano)
    {

        $sql = "SELECT 
                VC.[idProduto], 
                C.nomeProduto,
                Sum(VC.qtd) AS qtdTotal, 
                Sum(VC.valor) AS valorTotal,
                Max(VC.dataHora) as ultimaData,
                VC.ano,
                VC.mes
            FROM   [Auper].[dbo].[vendasqtdvaloresprodutos]  VC
            INNER JOIN  [Auper].[dbo].[produtos] C 
            ON C.idProduto = VC.idProduto
            WHERE VC.idProduto = :idProduto
               AND VC.ano = :ano
            GROUP  BY VC.idProduto,
                      C.nomeProduto,
                      VC.ano,
                      VC.mes
                      ORDER BY VC.idProduto, ano desc, mes desc";

      $stmt = $this->db->prepare($sql);

      $stmt->bindValue("idProduto",$idProduto);
      $stmt->bindValue("ano",$ano);

      $stmt->execute();

      $produtos = $stmt->fetchAll();
      return $produtos;
    }


    public function loadById($idProduto)
    {
      
      $stmt = $this->db->prepare("SELECT 
       *  
        FROM   [Auper].[dbo].[produtos] 
        WHERE idProduto = :idProduto");

      $stmt->bindValue("idProduto",$idProduto);

      $stmt->execute();

      if (!$produtos = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $produtos[0];

    }




}

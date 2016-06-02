<?Php
namespace Auper\Vendedores\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DateOperation;

class VendedoresDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

   public function insert(array $vendedores)
    {

        $this->dropTable("Auper.dbo.vendedores");
        foreach ($vendedores as $line) {
            $this->insertTableArray('Auper.dbo.vendedores', $line);
        }

    }

    public function update($vendedores){

        foreach($vendedores as $vendedor){
          $id = $vendedor['idVendedor'];
          unset($vendedor['idVendedor']);
          $this->updateTableById('Auper.dbo.vendedores', $vendedor , array('idVendedor' => $id));
        }
    }

    public function loadVendasForVendedor($idVendedor, $ano)
    {

        $sql = "SELECT 
                VC.[idVendedor], 
                C.nomeVendedor,
                Sum(VC.qtd) AS qtdTotal, 
                Sum(VC.valor) AS valorTotal,
                Max(VC.dataHora) as ultimaData,
                VC.ano,
                VC.mes
            FROM   [Auper].[dbo].[vendasqtdvaloresVendedores]  VC
            INNER JOIN  [Auper].[dbo].[Vendedores] C 
            ON C.idVendedor = VC.idVendedor
            WHERE VC.idVendedor = :idVendedor
               AND VC.ano = :ano
            GROUP  BY VC.idVendedor,
                      C.nomeVendedor,
                      VC.ano,
                      VC.mes
                      ORDER BY VC.idVendedor, ano desc, mes desc";

      $stmt = $this->db->prepare($sql);

      $stmt->bindValue("idVendedor",$idVendedor);
      $stmt->bindValue("ano",$ano);

      $stmt->execute();

      $clientes = $stmt->fetchAll();
      return $clientes;
    }


    public function loadById($idVendedor)
    {
      
      $stmt = $this->db->prepare("SELECT 
       *  
        FROM   [Auper].[dbo].[vendedores] 
        WHERE idVendedor = :idVendedor");

      $stmt->bindValue("idVendedor",$idVendedor);

      $stmt->execute();

      if (!$vendedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $vendedores[0];

    }
}

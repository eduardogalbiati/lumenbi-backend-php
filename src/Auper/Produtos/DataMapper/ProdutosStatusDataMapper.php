<?Php
namespace Auper\Produtos\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DateOperation;

use Auper\Produtos\DataMapper\ProdutosDataMapper;


class ProdutosStatusDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

     public function insert(array $produtos)
    {

        //$this->dropTable("Auper.dbo.ProdutosStatus");
        foreach ($produtos as $line) {
            $this->insertTableArray('Auper.dbo.produtosStatus', $line);
        }

    }
 public function loadProdutosPositivos($mesAlvo, $anoAlvo, $intervalo)
    {
        $sql = "SELECT CS.[idProduto], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     idStatus = '1',
                     CS.periodoStatus,
                     C.nomeProduto
              FROM   [Auper].[dbo].[produtosStatus] CS 
                     INNER JOIN [Auper].[dbo].[produtos] C 
                             ON C.idProduto = CS.idProduto 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '1'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idProduto, CS.periodoStatus, C.nomeProduto
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$produtos = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $produtos;


    }


    public function loadProdutosNegativos ($mesAlvo, $anoAlvo, $intervalo)
    {
       $sql = "SELECT CS.[idProduto], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData,
                     idStatus = '2',
                     CS.periodoStatus,
                     C.nomeProduto
              FROM   [Auper].[dbo].[produtosStatus] CS 
                     INNER JOIN [Auper].[dbo].[produtos] C 
                             ON C.idProduto = CS.idProduto 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '2'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idProduto, CS.periodoStatus, C.nomeProduto
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$produtos = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $produtos;

    }


    public function loadProdutosNovos ($mesAlvo, $anoAlvo, $intervalo)
    {
     $sql = "SELECT CS.[idProduto], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     idStatus = '3',
                     CS.periodoStatus,
                     C.nomeProduto
              FROM   [Auper].[dbo].[produtosStatus] CS 
                     INNER JOIN [Auper].[dbo].[produtos] C 
                             ON C.idProduto = CS.idProduto 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '3'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idProduto, CS.periodoStatus, C.nomeProduto
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$produtos = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $produtos;
    }

    public function loadProdutosRecuperados ($mesAlvo, $anoAlvo, $intervalo)
    {

      $sql = "SELECT CS.[idProduto], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     C.nomeProduto,
                     idStatus = '4',
                     CS.periodoStatus
              FROM   [Auper].[dbo].[produtosStatus] CS 
                     INNER JOIN [Auper].[dbo].[produtos] C 
                             ON C.idProduto = CS.idProduto 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '4'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idProduto, CS.periodoStatus, C.nomeProduto
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$produtos = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $produtos;


    }

     public function loadProdutosRegulares ($mesAlvo, $anoAlvo, $intervalo)
    {
      $sql = "SELECT CS.[idProduto], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     C.nomeProduto,
                     idStatus = '5',
                     CS.periodoStatus
              FROM   [Auper].[dbo].[produtosStatus] CS 
                     INNER JOIN [Auper].[dbo].[produtos] C 
                             ON C.idProduto = CS.idProduto 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '5'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idProduto, CS.periodoStatus, C.nomeProduto
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$produtos = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $produtos;


    }

    public function loadprodutosStatus($mesAlvo, $anoAlvo, $intervalo)
    {
      $prod = array();
      $prod[] = $this->loadProdutosPositivos($mesAlvo, $anoAlvo, $intervalo);
      $prod[] = $this->loadProdutosNegativos($mesAlvo, $anoAlvo, $intervalo);
      $prod[] = $this->loadProdutosRecuperados($mesAlvo, $anoAlvo, $intervalo);
      $prod[] = $this->loadProdutosNovos($mesAlvo, $anoAlvo, $intervalo);
      $prod[] = $this->loadProdutosRegulares($mesAlvo, $anoAlvo, $intervalo);
 
      foreach ($prod as $k => $v) {
        foreach ($v as $produto) {
          $arr[$produto['idProduto']] = $produto; 
        }
      }
    
      return $arr;
    }

    public function loadStatusHistorico($idProduto, $intervalo)
    {
       $sql = "SELECT CS.[idProduto], 
                     CS.ano,
                     CS.mes,
                     CS.idStatus,
                     CS.periodoStatus
              FROM   [Auper].[dbo].[produtosStatus] CS 
                     INNER JOIN [Auper].[dbo].[produtos] C 
                             ON C.idProduto = CS.idProduto 
              WHERE CS.idProduto = :idProduto
                AND CS.intervalo = :intervalo
              ORDER BY
                ultimadata DESC 

                ";
                 $stmt = $this->db->prepare($sql);


      $stmt->bindValue("idProduto",$idProduto);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$produtos = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $produtos;

    }

}

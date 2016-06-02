<?Php
namespace Auper\Fornecedores\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DateOperation;

use Auper\Fornecedores\DataMapper\FornecedoresDataMapper;



class FornecedoresStatusDataMapper extends AbstractDataMapper
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
            $this->insertTableArray('Auper.dbo.fornecedoresStatus', $line);
        }

    }


    public function loadFornecedoresPositivos($mesAlvo, $anoAlvo, $intervalo)
    {
        $sql = "SELECT CS.[idFornecedor], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     idStatus = '1',
                     CS.periodoStatus,
                     C.nomeFornecedor
              FROM   [Auper].[dbo].[fornecedoresStatus] CS 
                     INNER JOIN [Auper].[dbo].[fornecedores] C 
                             ON C.idFornecedor = CS.idFornecedor 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '1'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idFornecedor, CS.periodoStatus, C.nomeFornecedor
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores;


    }


    public function loadFornecedoresNegativos ($mesAlvo, $anoAlvo, $intervalo)
    {
       $sql = "SELECT CS.[idFornecedor], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData,
                     idStatus = '2',
                     CS.periodoStatus,
                     C.nomeFornecedor
              FROM   [Auper].[dbo].[fornecedoresStatus] CS 
                     INNER JOIN [Auper].[dbo].[fornecedores] C 
                             ON C.idFornecedor = CS.idFornecedor 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '2'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idFornecedor, CS.periodoStatus, C.nomeFornecedor
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores;

    }


    public function loadFornecedoresNovos ($mesAlvo, $anoAlvo, $intervalo)
    {
     $sql = "SELECT CS.[idFornecedor], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     idStatus = '3',
                     CS.periodoStatus,
                     C.nomeFornecedor
              FROM   [Auper].[dbo].[fornecedoresStatus] CS 
                     INNER JOIN [Auper].[dbo].[fornecedores] C 
                             ON C.idFornecedor = CS.idFornecedor 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '3'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idFornecedor, CS.periodoStatus, C.nomeFornecedor
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores;
    }

    public function loadFornecedoresRecuperados ($mesAlvo, $anoAlvo, $intervalo)
    {

      $sql = "SELECT CS.[idFornecedor], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     C.nomeFornecedor,
                     idStatus = '4',
                     CS.periodoStatus
              FROM   [Auper].[dbo].[fornecedoresStatus] CS 
                     INNER JOIN [Auper].[dbo].[fornecedores] C 
                             ON C.idFornecedor = CS.idFornecedor 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '4'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idFornecedor, CS.periodoStatus, C.nomeFornecedor
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores;


    }

     public function loadFornecedoresRegulares ($mesAlvo, $anoAlvo, $intervalo)
    {
      $sql = "SELECT CS.[idFornecedor], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     C.nomeFornecedor,
                     idStatus = '5',
                     CS.periodoStatus
              FROM   [Auper].[dbo].[fornecedoresStatus] CS 
                     INNER JOIN [Auper].[dbo].[fornecedores] C 
                             ON C.idFornecedor = CS.idFornecedor 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '5'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idFornecedor, CS.periodoStatus, C.nomeFornecedor
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores;


    }

    public function loadFornecedoresStatus($mesAlvo, $anoAlvo, $intervalo)
    {
      $for = array();
      $for[] = $this->loadFornecedoresPositivos($mesAlvo, $anoAlvo, $intervalo);
      $for[] = $this->loadFornecedoresNegativos($mesAlvo, $anoAlvo, $intervalo);
      $for[] = $this->loadFornecedoresRecuperados($mesAlvo, $anoAlvo, $intervalo);
      $for[] = $this->loadFornecedoresNovos($mesAlvo, $anoAlvo, $intervalo);
      $for[] = $this->loadFornecedoresRegulares($mesAlvo, $anoAlvo, $intervalo);
 
      foreach ($for as $k => $v) {
        foreach ($v as $fornecedor) {
          $arr[$fornecedor['idFornecedor']] = $fornecedor; 
        }
      }
    
      return $arr;
    }

    public function loadStatusHistorico($idFornecedor, $intervalo)
    {
       $sql = "SELECT CS.[idFornecedor], 
                     CS.ano,
                     CS.mes,
                     CS.idStatus,
                     CS.periodoStatus
              FROM   [Auper].[dbo].[fornecedoresStatus] CS 
                     INNER JOIN [Auper].[dbo].[fornecedores] C 
                             ON C.idFornecedor = CS.idFornecedor 
              WHERE CS.idFornecedor = :idFornecedor
                AND CS.intervalo = :intervalo
              ORDER BY
                ultimadata DESC 

                ";
                 $stmt = $this->db->prepare($sql);


      $stmt->bindValue("idFornecedor",$idFornecedor);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores;

    }



   

}

<?Php
namespace Auper\Clientes\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DateOperation;

use Auper\Clientes\DataMapper\ClientesDataMapper;



class ClientesStatusDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

     public function insert(array $clientes)
    {

        //$this->dropTable("Auper.dbo.clientesStatus");
        foreach ($clientes as $line) {
            $this->insertTableArray('Auper.dbo.clientesStatus', $line);
        }

    }


    public function loadClientesPositivos($mesAlvo, $anoAlvo, $intervalo)
    {
        $sql = "SELECT CS.[idCliente], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     idStatus = '1',
                     CS.periodoStatus,
                     C.nomeCliente
              FROM   [Auper].[dbo].[clientesStatus] CS 
                     INNER JOIN [Auper].[dbo].[clientes] C 
                             ON C.idcliente = CS.idcliente 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '1'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idCliente, CS.periodoStatus, C.nomeCliente
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes;


    }


    public function loadClientesNegativos ($mesAlvo, $anoAlvo, $intervalo)
    {
       $sql = "SELECT CS.[idCliente], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData,
                     idStatus = '2',
                     CS.periodoStatus,
                     C.nomeCliente
              FROM   [Auper].[dbo].[clientesStatus] CS 
                     INNER JOIN [Auper].[dbo].[clientes] C 
                             ON C.idcliente = CS.idcliente 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '2'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idCliente, CS.periodoStatus, C.nomeCliente
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes;

    }


    public function loadClientesNovos ($mesAlvo, $anoAlvo, $intervalo)
    {
     $sql = "SELECT CS.[idCliente], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     idStatus = '3',
                     CS.periodoStatus,
                     C.nomeCliente
              FROM   [Auper].[dbo].[clientesStatus] CS 
                     INNER JOIN [Auper].[dbo].[clientes] C 
                             ON C.idcliente = CS.idcliente 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '3'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idCliente, CS.periodoStatus, C.nomeCliente
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes;
    }

    public function loadClientesRecuperados ($mesAlvo, $anoAlvo, $intervalo)
    {

      $sql = "SELECT CS.[idCliente], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     C.nomeCliente,
                     idStatus = '4',
                     CS.periodoStatus
              FROM   [Auper].[dbo].[clientesStatus] CS 
                     INNER JOIN [Auper].[dbo].[clientes] C 
                             ON C.idcliente = CS.idcliente 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '4'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idCliente, CS.periodoStatus, C.nomeCliente
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes;


    }

     public function loadClientesRegulares ($mesAlvo, $anoAlvo, $intervalo)
    {
      $sql = "SELECT CS.[idCliente], 
                     Sum(CS.qtdPeriodo)     AS qtdTotal, 
                     Sum(CS.valorPeriodo)   AS valorTotal, 
                     Max(CS.ultimaData)AS ultimaData, 
                     C.nomeCliente,
                     idStatus = '5',
                     CS.periodoStatus
              FROM   [Auper].[dbo].[clientesStatus] CS 
                     INNER JOIN [Auper].[dbo].[clientes] C 
                             ON C.idcliente = CS.idcliente 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.idStatus = '5'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idCliente, CS.periodoStatus, C.nomeCliente
              ORDER BY
                ultimadata DESC 

                ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("anoAlvo",$anoAlvo);
      $stmt->bindValue("mesAlvo",$mesAlvo);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes;


    }

    public function loadClientesStatus($mesAlvo, $anoAlvo, $intervalo)
    {
      $cli = array();
      $cli[] = $this->loadClientesPositivos($mesAlvo, $anoAlvo, $intervalo);
      $cli[] = $this->loadClientesNegativos($mesAlvo, $anoAlvo, $intervalo);
      $cli[] = $this->loadClientesRecuperados($mesAlvo, $anoAlvo, $intervalo);
      $cli[] = $this->loadClientesNovos($mesAlvo, $anoAlvo, $intervalo);
      $cli[] = $this->loadClientesRegulares($mesAlvo, $anoAlvo, $intervalo);
 
      foreach ($cli as $k => $v) {
        foreach ($v as $cliente) {
          $arr[$cliente['idCliente']] = $cliente; 
        }
      }
    
      return $arr;
    }

     public function loadStatusHistorico($idCliente, $intervalo)
    {
       $sql = "SELECT CS.[idCliente], 
                     CS.ano,
                     CS.mes,
                     CS.idStatus,
                     CS.periodoStatus
              FROM   [Auper].[dbo].[clientesStatus] CS 
                     INNER JOIN [Auper].[dbo].[clientes] C 
                             ON C.idCliente = CS.idCliente 
              WHERE CS.idCliente = :idCliente
                AND CS.intervalo = :intervalo
              ORDER BY
                ultimadata DESC 

                ";
                 $stmt = $this->db->prepare($sql);


      $stmt->bindValue("idCliente",$idCliente);
      $stmt->bindValue("intervalo",$intervalo);


      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes;

    }


   

}

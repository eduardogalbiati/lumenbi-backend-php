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
                     C.nomecliente   AS cliente 
              FROM   [Auper].[dbo].[clientesStatus] CS 
                     INNER JOIN [Auper].[dbo].[clientes] C 
                             ON C.idcliente = CS.idcliente 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.status = 'Positivo'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idCliente, C.nomecliente
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
                     C.nomecliente   AS cliente 
              FROM   [Auper].[dbo].[clientesStatus] CS 
                     INNER JOIN [Auper].[dbo].[clientes] C 
                             ON C.idcliente = CS.idcliente 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.status = 'Negativo'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idCliente, C.nomecliente
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
                     C.nomecliente   AS cliente 
              FROM   [Auper].[dbo].[clientesStatus] CS 
                     INNER JOIN [Auper].[dbo].[clientes] C 
                             ON C.idcliente = CS.idcliente 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.status = 'Novo'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idCliente, C.nomecliente
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
                     C.nomecliente   AS cliente 
              FROM   [Auper].[dbo].[clientesStatus] CS 
                     INNER JOIN [Auper].[dbo].[clientes] C 
                             ON C.idcliente = CS.idcliente 
              WHERE CS.ano = :anoAlvo
                AND CS.mes = :mesAlvo
                AND CS.intervalo = :intervalo
                AND CS.status = 'Recuperado'
                 GROUP BY CS.ano, CS.mes, CS.intervalo, CS.idCliente, C.nomecliente
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


    public function loadComparativoClientes($mesAlvo, $anoAlvo, $intervalo)
    {
      $cPos = $this->loadClientesPositivos($mesAlvo, $anoAlvo, $intervalo);
      $cNeg = $this->loadClientesNegativos($mesAlvo, $anoAlvo, $intervalo);
      $cRec = $this->loadClientesRecuperados($mesAlvo, $anoAlvo, $intervalo);
      $cNov = $this->loadClientesNovos($mesAlvo, $anoAlvo, $intervalo);

      $clientesDM = new ClientesDataMapper($this->db);
      $cTotal = $clientesDM->getCountClientes($ativo = 'S');

      $nPos = count($cPos);
      $nRec = count($cRec);
      $nNov = count($cNov);
      $nNeg = count($cNeg);

      return array(
        'itens' => array(
          'pos' => $nPos,
          'rec' => $nRec,
          'nov' => $nNov,
          'neg' => $nNeg,
        ),
        'mes' => $mesAlvo,
        'ano' => $anoAlvo,
        'intervalo' => $intervalo,
        'total' => $cTotal,
      );

    }

   

}

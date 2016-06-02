<?Php
namespace Auper\Clientes\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class ExternalClientesDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function loadClientes()
    {
     
        $stmt = $this->db->prepare("SELECT [codigo], 
                contato as [nome], 
                nome as [fantasia],
                [endereco], 
                [cep], 
                [bairro], 
                [cidade], 
                [uf], 
                [telefone], 
                ultima_venda as [ultcompra],
                ultima_venda as [dtinc],
                grupo as [motivobloqueio1]
            FROM   [shoficina].[dbo].[Clientes] 
            WHERE codigo NOT IN (SELECT idCliente as codigo FROM [Auper].[dbo].[Clientes] )
  ");

        $stmt->execute();

        if (!$clientes = $stmt->fetchAll()) {
           // throw new \Exception('Nenhuma venda lançada para o período');
        }

        return $clientes;
    }

     public function loadClientesForUpdate($dtInicio)
    {
     
        $stmt = $this->db->prepare("SELECT [codigo], 
                [nome], 
                [fantasia],
                [dtinc], 
                [endereco], 
                [cep], 
                [bairro], 
                [cidade], 
                [uf], 
                [fone], 
                [ultcompra],
                [motivobloqueio1] ,
                [ativo],
                [status]
            FROM   [Industrial].[dbo].[Clientes] 
            WHERE DataAlteracao >= :dtInicio
  ");
        $stmt->bindValue("dtInicio",$dtInicio);
        $stmt->execute();

        if (!$clientes = $stmt->fetchAll()) {
           // throw new \Exception('Nenhuma venda lançada para o período');
        }

        return $clientes;
    }

    public function loadClientesNovos ($meses, $limite = false)
    {
      if($limite == ''){
        $limite = date('Y-m-d');
      }
      $date = new \DateTime(date("Y-m-d"));
      $date->sub(new \DateInterval('P'.$meses.'M'));
      //echo $date->format('m');;die;
      $dataHora = $date->format('Y-m-d');
      $stmt = $this->db->prepare("SELECT [cliente], 
               Sum(qtd) AS qtdTotal, 
               SUM(valor) as valorTotal,
               MAX(dataHora)as ultimaData
        FROM   [Auper].[dbo].[vendasqtdvaloresclientes] 
        WHERE  datahora >= :dataHora 
          AND  datahora <= :dataHoraLimite 
        GROUP  BY cliente 
        HAVING Sum(qtd) = 1 
        ORDER  BY Sum(qtd) , ultimaData desc
");

      $stmt->bindValue("dataHora",$dataHora);
      $stmt->bindValue("dataHoraLimite",$limite);
      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes;


    }

    public function loadClientesNegativos ($meses, $limite = false)
    {
      if($limite == ''){
        $limite = date('Y-m-d');
      }
      $date = new \DateTime(date("Y-m-d"));
      $date->sub(new \DateInterval('P'.$meses.'M'));
      //echo $date->format('m');;die;
      $dataHora = $date->format('Y-m-d');
      $stmt = $this->db->prepare("SELECT [cliente], 
               Sum(qtd) AS qtdTotal, 
               SUM(valor) as valorTotal,
               MAX(dataHora)as ultimaData
        FROM   [Auper].[dbo].[vendasqtdvaloresclientes] 

        GROUP  BY cliente 
        HAVING  MAX(dataHora) < :dataHora 
        ORDER  BY Sum(qtd) , ultimaData desc
");

      $stmt->bindValue("dataHora",$dataHora);
     // $stmt->bindValue("dataHoraLimite",$limite);
      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes;


    }

    public function loadClientesRecuperados ($meses, $limite = false)
    {
      if($limite == ''){
        $limite = date('Y-m-d');
      }
      $date = new \DateTime(date("Y-m-d"));
      $date->sub(new \DateInterval('P'.$meses.'M'));
      //echo $date->format('m');;die;
      $dataHora = $date->format('Y-m-d');
      $stmt = $this->db->prepare("SELECT [cliente], 
               Sum(qtd) AS qtdTotal, 
               SUM(valor) as valorTotal,
               MAX(dataHora)as ultimaData
        FROM   [Auper].[dbo].[vendasqtdvaloresclientes] 

        GROUP  BY cliente 
        HAVING  MAX(dataHora) < :dataHora 
        ORDER  BY Sum(qtd) , ultimaData desc
");

      $stmt->bindValue("dataHora",$dataHora);
     // $stmt->bindValue("dataHoraLimite",$limite);
      $stmt->execute();

      if (!$clientes = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $clientes;


    }
}

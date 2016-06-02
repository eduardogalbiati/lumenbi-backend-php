<?Php
namespace Auper\Fornecedores\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class ExternalFornecedoresDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function loadFornecedores()
    {
     
        $stmt = $this->db->prepare("SELECT [Codigo]
      ,Razao as [Nome]
      ,[Endereco]
      ,[Fantasia]
      ,ULTIMA_COMPRA as [DtCad]
      ,[Cep]
      ,[Bairro]
      ,[Cidade]
      ,[Uf]

      ,[Contato]
      ,Telefone as [Fone]
      ,[Fax]
      ,Email as [E_mail]
      ,[Obs]
            FROM   [shoficina].[dbo].[Fornecedores] 
            WHERE codigo NOT IN (SELECT idFornecedor as codigo FROM [Auper].[dbo].[Fornecedores] )
  ");

        $stmt->execute();

        if (!$fornecedores = $stmt->fetchAll()) {
           // throw new \Exception('Nenhuma venda lançada para o período');
        }

        return $fornecedores;
    }

    

    public function loadFornecedoresNovos ($meses, $limite = false)
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
        FROM   [Auper].[dbo].[comprasqtdvaloresfornecedores] 
        WHERE  datahora >= :dataHora 
          AND  datahora <= :dataHoraLimite 
        GROUP  BY cliente 
        HAVING Sum(qtd) = 1 
        ORDER  BY Sum(qtd) , ultimaData desc
");

      $stmt->bindValue("dataHora",$dataHora);
      $stmt->bindValue("dataHoraLimite",$limite);
      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores;


    }

    public function loadFornecedoresNegativos ($meses, $limite = false)
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
        FROM   [Auper].[dbo].[comprasqtdvaloresfornecedores] 

        GROUP  BY cliente 
        HAVING  MAX(dataHora) < :dataHora 
        ORDER  BY Sum(qtd) , ultimaData desc
");

      $stmt->bindValue("dataHora",$dataHora);
     // $stmt->bindValue("dataHoraLimite",$limite);
      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores;


    }

    public function loadFornecedoresRecuperados ($meses, $limite = false)
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
        FROM   [Auper].[dbo].[comprasqtdvaloresfornecedores] 

        GROUP  BY cliente 
        HAVING  MAX(dataHora) < :dataHora 
        ORDER  BY Sum(qtd) , ultimaData desc
");

      $stmt->bindValue("dataHora",$dataHora);
     // $stmt->bindValue("dataHoraLimite",$limite);
      $stmt->execute();

      if (!$fornecedores = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $fornecedores;


    }
}

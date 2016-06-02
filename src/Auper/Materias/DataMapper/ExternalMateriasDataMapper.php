<?Php
namespace Auper\Materias\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class ExternalMateriasDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function loadMaterias()
    {
     
        $stmt = $this->db->prepare("SELECT [Codigo]
              ,[Descricao]
            FROM   [Industrial].[dbo].[Materia] 
            WHERE codigo NOT IN (SELECT idMateria as codigo FROM [Auper].[dbo].[materias] )
  ");

        $stmt->execute();

        if (!$materias = $stmt->fetchAll()) {
           // throw new \Exception('Nenhuma venda lançada para o período');
        }

        return $materias;
    }

    

    public function loadMateriasNovos ($meses, $limite = false)
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
        FROM   [Auper].[dbo].[comprasqtdvaloresmaterias] 
        WHERE  datahora >= :dataHora 
          AND  datahora <= :dataHoraLimite 
        GROUP  BY cliente 
        HAVING Sum(qtd) = 1 
        ORDER  BY Sum(qtd) , ultimaData desc
");

      $stmt->bindValue("dataHora",$dataHora);
      $stmt->bindValue("dataHoraLimite",$limite);
      $stmt->execute();

      if (!$materias = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $materias;


    }

    public function loadMateriasNegativos ($meses, $limite = false)
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
        FROM   [Auper].[dbo].[comprasqtdvaloresmaterias] 

        GROUP  BY cliente 
        HAVING  MAX(dataHora) < :dataHora 
        ORDER  BY Sum(qtd) , ultimaData desc
");

      $stmt->bindValue("dataHora",$dataHora);
     // $stmt->bindValue("dataHoraLimite",$limite);
      $stmt->execute();

      if (!$materias = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $materias;


    }

    public function loadMateriasRecuperados ($meses, $limite = false)
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
        FROM   [Auper].[dbo].[comprasqtdvaloresmaterias] 

        GROUP  BY cliente 
        HAVING  MAX(dataHora) < :dataHora 
        ORDER  BY Sum(qtd) , ultimaData desc
");

      $stmt->bindValue("dataHora",$dataHora);
     // $stmt->bindValue("dataHoraLimite",$limite);
      $stmt->execute();

      if (!$materias = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $materias;


    }
}

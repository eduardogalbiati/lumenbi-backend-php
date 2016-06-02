<?Php
namespace Auper\Materias\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DateOperation;


class MateriasDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

     public function insert(array $materias)
    {

        $this->dropTable("Auper.dbo.materias");
        foreach ($materias as $line) {
            $this->insertTableArray('Auper.dbo.materias', $line);
        }

    }

    public function update($materias){

        foreach($materias as $materia){
          $id = $materia['idMateria'];
          unset($materia['idMateria']);
          $this->updateTableById('Auper.dbo.materias', $materia , array('idMateria' => $id));
        }
    }


    public function loadMateriasPositivos($mesAlvo, $anoAlvo, $intervalo)
    {
        $ultimoDiaMesAlvo = date("t", mktime(0,0,0,$mesAlvo,'01',$anoAlvo));

        $date = new \DateTime($anoAlvo.'-'.$mesAlvo.'-01');
        $dateOp = new DateOperation($date);
        
        $dateOp->subMonth($intervalo);
        $mesInt = $dateOp->getMonth();
        $anoInt = $dateOp->getYear();

        for($i=0;$i<=$intervalo;$i++) {
            if($i != 0){
              $query .= ') >= 1 AND (';
            }else{
              $query .= '(';
            }
            $query .= "SELECT Count(1) as QTD 
                      FROM   [Auper].[dbo].[comprasqtdvaloresmaterias] vc2 
                      WHERE  vc2.idMateria =  VC.idMateria  
                             and vc2.ano ='".$dateOp->getYear()."'
                             and vc2.mes ='".$dateOp->getMonth()."'";
                      $dateOp->addMonth(1);

        }
        $query .= ') >=1';

        $sql = "SELECT 
                VC.[idMateria], 
                C.nomeCliente,
                Sum(VC.qtd) AS qtdTotal, 
                Sum(VC.valor) AS valorTotal,
                Max(VC.dataHora) as ultimaData
            FROM   [Auper].[dbo].[comprasqtdvaloresmaterias]  VC
            INNER JOIN  [Auper].[dbo].[materias] C 
            ON C.idMateria = VC.idMateria
            WHERE  VC.datahora <= :dataHoraAlvo
              AND  VC.datahora >= :dataHoraInt
            GROUP  BY VC.idMateria,
                      C.nomeCliente 
             HAVING ".$query;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue("dataHoraAlvo",$anoAlvo.'-'.$mesAlvo.'-'.$ultimoDiaMesAlvo);
        $stmt->bindValue("dataHoraInt",$anoInt.'-'.$mesInt.'-01');
        $stmt->execute();

        if (!$materias = $stmt->fetchAll()) {
           // throw new \Exception('Nenhuma venda lançada para o período');
        }

        
        return $materias;


    }


    public function loadMateriasNegativos ($mesAlvo, $anoAlvo, $intervalo)
    {
        $ultimoDiaMesAlvo = date("t", mktime(0,0,0,$mesAlvo,'01',$anoAlvo));
                $date = new \DateTime($anoAlvo.'-'.$mesAlvo.'-01');

        $dateOp = new DateOperation($date);
        
        $dateOp->subMonth($intervalo);
        $mesInt = $dateOp->getMonth();
        $anoInt = $dateOp->getYear();

        $stmt = $this->db->prepare("SELECT VC.idMateria,
               C.nomeCliente as cliente, 
               Sum(VC.qtd) AS qtdTotal, 
               SUM(VC.valor) as valorTotal,
               MAX(VC.dataHora)as ultimaData
        FROM   [Auper].[dbo].[comprasqtdvaloresmaterias] VC
        INNER JOIN  [Auper].[dbo].[materias] C 
            ON C.idMateria = VC.idMateria
        GROUP  BY VC.idMateria, C.nomeCliente 
        HAVING  MAX(VC.dataHora) <= :dataMin AND MAX(VC.datahora) >= :dataMin2 and
               (SELECT COUNT(1) FROM [Auper].[dbo].[comprasqtdvaloresmaterias] vc2 WHERE vc2.idMateria=VC.idMateria) > 1

        ORDER  BY Sum(VC.qtd) , ultimaData desc
");

      $stmt->bindValue("dataMin",$anoInt.'-'.$mesInt.'-01');

      $dateOp->subMonth(1);
      $mesInt = $dateOp->getMonth();
      $anoInt = $dateOp->getYear();

      $stmt->bindValue("dataMin2",$anoInt.'-'.$mesInt.'-01');
     // $stmt->bindValue("dataHoraLimite",$limite);
      $stmt->execute();

      if (!$materias = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $materias;


    }


    public function loadMateriasNovos ($mesAlvo, $anoAlvo, $intervalo)
    {
      $ultimoDiaMesAlvo = date("t", mktime(0,0,0,$mesAlvo,'01',$anoAlvo));
      $date = new \DateTime($anoAlvo.'-'.$mesAlvo.'-01');
      $dateOp = new DateOperation($date);
      
      $dateOp->subMonth($intervalo);
      $mesInt = $dateOp->getMonth();
      $anoInt = $dateOp->getYear();

      $stmt = $this->db->prepare("SELECT VC.[idMateria],

               Sum(VC.qtd) AS qtdTotal, 
               SUM(VC.valor) as valorTotal,
               MAX(VC.dataHora)as ultimaData,
               C.nomeCliente as cliente
        FROM   [Auper].[dbo].[comprasqtdvaloresmaterias] VC 
         INNER JOIN  [Auper].[dbo].[materias] C 
            ON C.idMateria = VC.idMateria
        WHERE  VC.datahora <= :dataHoraAlvo
                AND  VC.datahora >= :dataHoraInt
        GROUP  BY VC.idMateria, C.nomeCliente 
        HAVING (SELECT COUNT(1) FROM [Auper].[dbo].[comprasqtdvaloresmaterias] vc2 WHERE vc2.idMateria=VC.idMateria) = 1
        ORDER  BY Sum(VC.qtd) , ultimaData desc
");

      $stmt->bindValue("dataHoraAlvo",$anoAlvo.'-'.$mesAlvo.'-'.$ultimoDiaMesAlvo);
      $stmt->bindValue("dataHoraInt",$anoInt.'-'.$mesInt.'-01');
      $stmt->execute();

      if (!$materias = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $materias;


    }

    public function loadMateriasRecuperados ($mesAlvo, $anoAlvo, $intervalo)
    {
      $ultimoDiaMesAlvo = date("t", mktime(0,0,0,$mesAlvo,'01',$anoAlvo));
      $date = new \DateTime($anoAlvo.'-'.$mesAlvo.'-01');
      $dateOp = new DateOperation($date);
      
      $dateOp->subMonth($intervalo);
      $mesInt = $dateOp->getMonth();
      $anoInt = $dateOp->getYear();

      //echo $date->format('m');;die;
      $sql = "SELECT VC.[idMateria], 
                     Sum(VC.qtd)     AS qtdTotal, 
                     Sum(VC.valor)   AS valorTotal, 
                     Max(VC.datahora)AS ultimaData, 
                     C.nomecliente   AS cliente 
              FROM   [Auper].[dbo].[comprasqtdvaloresmaterias] VC 
                     INNER JOIN [Auper].[dbo].[materias] C 
                             ON C.idMateria = VC.idMateria 
              WHERE VC.datahora <= :dataHoraAlvo
                AND  VC.datahora >= :dataHoraInt 
              GROUP  BY VC.idMateria, 
                        C.nomecliente 
              HAVING (SELECT Count(1) 
                      FROM   [Auper].[dbo].[comprasqtdvaloresmaterias] vc2 
                      WHERE  vc2.idMateria = VC.idMateria 
                             AND vc2.datahora <= :dataHoraAlvo2
                             AND  vc2.datahora >= :dataHoraInt2
                      ) = 1 
                     AND (SELECT Month(Max(datahora)) 
                          FROM   [Auper].[dbo].[comprasqtdvaloresmaterias] vc3 
                          WHERE  vc3.idMateria = VC.idMateria 
                                 AND vc3.datahora <= :dataHoraAlvo3
                             AND  vc3.datahora >= :dataHoraInt3
                      ) = :mesAlvo3
                     AND (SELECT COUNT(1) 
                          FROM   [Auper].[dbo].[comprasqtdvaloresmaterias] vc2 
                          WHERE   vc2.idMateria=VC.idMateria
                      ) > 1

              ORDER  BY Sum(VC.qtd), 
                        ultimadata DESC ";

      $stmt = $this->db->prepare($sql);


      $stmt->bindValue("dataHoraAlvo",$anoAlvo.'-'.$mesAlvo.'-'.$ultimoDiaMesAlvo);
      $stmt->bindValue("dataHoraInt",$anoInt.'-'.$mesInt.'-01');
      $stmt->bindValue("dataHoraAlvo2",$anoAlvo.'-'.$mesAlvo.'-'.$ultimoDiaMesAlvo);
      $stmt->bindValue("dataHoraInt2",$anoInt.'-'.$mesInt.'-01');
      $stmt->bindValue("dataHoraAlvo3",$anoAlvo.'-'.$mesAlvo.'-'.$ultimoDiaMesAlvo);
      $stmt->bindValue("dataHoraInt3",$anoInt.'-'.$mesInt.'-01');


      $stmt->bindValue("mesAlvo3",$mesAlvo);

      $stmt->execute();

      if (!$materias = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $materias;


    }


    public function loadComparativoMaterias($mesAlvo, $anoAlvo, $intervalo)
    {
      $cPos = $this->loadMateriasPositivos($mesAlvo, $anoAlvo, $intervalo);
      $cNeg = $this->loadMateriasNegativos($mesAlvo, $anoAlvo, $intervalo);
      $cRec = $this->loadMateriasRecuperados($mesAlvo, $anoAlvo, $intervalo);
      $cNov = $this->loadMateriasNovos($mesAlvo, $anoAlvo, $intervalo);
      $cTotal = $this->getCountMaterias($ativo = 'S');
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

    public function loadTodos($dataInicio, $dataFim, $ativo)
    {
      
      $stmt = $this->db->prepare("SELECT 
          [idMateria], 
          [nomeCliente], 
          [telefone1], 
          [ativo], 
          [status]
              
        FROM   [Auper].[dbo].[materias] 
        WHERE dataHoraCad >= :dataInicio AND dataHoraCad <= :dataFim AND ativo=:ativo
        Order by nomeCliente asc
");

      $stmt->bindValue("dataInicio",$dataInicio);
      $stmt->bindValue("dataFim",$dataFim);
      $stmt->bindValue("ativo",$ativo);

      $stmt->execute();

      if (!$materias = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $materias;


    }

    public function getCountMaterias($ativo)
    {
      
      $stmt = $this->db->prepare("SELECT 
        COUNT(1)  as QTD   
        FROM   [Auper].[dbo].[materias] 
        WHERE ativo = :ativo");

      $stmt->bindValue("ativo",$ativo);

      $stmt->execute();

      if (!$materias = $stmt->fetchAll()) {
         // throw new \Exception('Nenhuma venda lançada para o período');
      }

      return $materias[0]['QTD'];
      return $materias;


    }

    
}

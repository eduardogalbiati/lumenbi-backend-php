<?Php
namespace Auper\Faturamento\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class FaturamentoQtdValoresDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertFaturamentoMensal(array $faturas)
    {

        $this->dropTable("Auper.dbo.faturamentoQtdValores");
        foreach ($faturas as $line) {
            $this->insertTableArray('Auper.dbo.faturamentoQtdValores', $line);
        }

    }


    protected function getAllData()
    {
        $stmt = $this->db->prepare("SELECT ano, mes, dia, valor as total
            FROM Auper.dbo.faturamentoQtdValores ORDER BY ano desc, mes desc, dia desc");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function loadAll()
    {
        $return = $this->getAllData();

        foreach ($return as $element) {
            unset($arr);
            $arr = array($element['ano'].'-'.$element['mes'].'-'.$element['dia'], $element['total']);
            $final[] = $arr;
        }

        return $final;
    }



    protected function getAnualData()
    {
        $stmt = $this->db->prepare("SELECT ano, SUM(valor) as total
            FROM Auper.dbo.faturamentoQtdValores GROUP BY ano  ORDER BY ano asc");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function loadAnual()
    {
        $return = $this->getAnualData();

        foreach ($return as $element) {
            unset($arr);
            $arr = array($element['ano'], $element['total']);
            $final[] = $arr;
        }

        return $final;
    }

    protected function getMensalData($ano)
    {
        $stmt = $this->db->prepare("SELECT mes, SUM(valor) as total
            FROM Auper.dbo.faturamentoQtdValores WHERE ano=:ano  GROUP BY ano, mes ORDER BY mes asc");
        $stmt->bindValue("ano", $ano);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function loadMensal($ano)
    {
        $return = $this->getMensalData($ano);

        foreach ($return as $element) {
            unset($arr);
            $arr = array($element['mes'], $element['total']);
            $final[] = $arr;
        }

        return $final;

    }

    protected function getDiarioData($mes, $ano)
    {
        $stmt = $this->db->prepare("SELECT dia, SUM(valor) as total
            FROM Auper.dbo.faturamentoQtdValores WHERE mes=:mes AND ano=:ano GROUP BY mes, dia");
        $stmt->bindValue("mes", $mes);
        $stmt->bindValue("ano", $ano);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function loadDiario($mes, $ano)
    {
        $return = $this->getDiarioData($mes, $ano);

        foreach ($return as $element) {
            unset($arr);
            $arr = array($element['dia'], $element['total']);
            $final[] = $arr;
        }

        return $final;
    }

    public function loadPeriodo($dataInicio, $dataFim, $view)
    {
        if($view == 'Anual'){
            $stmt = $this->db->prepare("SELECT ano, SUM(valor) as total
                FROM Auper.dbo.faturamentoQtdValores WHERE datahora >= :dataInicio AND datahora <= :dataFim GROUP BY ano");
        }
        if($view == 'Mensal'){
            $stmt = $this->db->prepare("SELECT ano, mes, SUM(valor) as total
                FROM Auper.dbo.faturamentoQtdValores WHERE datahora >= :dataInicio AND datahora <= :dataFim GROUP BY ano, mes");
        
        }
        if($view == 'Diario'){
            $stmt = $this->db->prepare("SELECT ano, mes, dia, SUM(valor) as total
                FROM Auper.dbo.faturamentoQtdValores WHERE datahora >= :dataInicio AND datahora <= :dataFim GROUP BY ano, mes, dia");
        }

        $stmt->bindValue("dataInicio", $dataInicio);
        $stmt->bindValue("dataFim", $dataFim);
        $stmt->execute();
       
        $return = $stmt->fetchAll();

        foreach ($return as $element) {
            unset($arr);
            if($view == 'Anual'){
                $index = $element['ano'];
            }
            if($view == 'Mensal'){
                $index = $element['ano'].'-'.$element['mes'];
            }
            if($view == 'Diario'){
                $index = $element['ano'].'-'.$element['mes'].'-'.$element['dia'];
            }

            $arr = array($index, $element['total']);
            $final[] = $arr;
        }

        return $final;
    }
}

<?Php
namespace Auper\Faturamento\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class FaturamentoQtdValoresClientesDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertFaturamentoMensal(array $faturas)
    {
        $this->dropTable("Auper.dbo.faturamentoQtdValoresClientes");
        foreach ($faturas as $line) {
            $this->insertTableArray('Auper.dbo.faturamentoQtdValoresClientes', $line);
        }

    }

    public function loadAbc($mes, $ano, $int)
    {
        $date = new \DateTime($ano.'-'.$mes.'-01');
        $date->add(new \DateInterval('P1M'));
        $mes = $date->format('m');

        $this->intFilter($mes);
        $this->intFilter($int);
        $this->intFilter($ano);
        
        $stmt = $this->db->prepare("SELECT C.idCliente, C.nomeCliente, SUM(VC.valor) as sumValor, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.faturamentoQtdValoresClientes VC INNER JOIN Auper.dbo.Clientes C on C.idCliente = VC.idCliente
            Where 
              datahora > dateadd(mm,-".$int.",'".$ano."-".($mes)."-01')
              AND  datahora < '".$ano."-".($mes)."-01'
            GROUP BY 
              C.idCliente, C.nomeCliente ");
        //$stmt->bindValue("dataIncio", $dataInicio);
        //$stmt->bindValue("dataFim", $dataFim);


        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;


    }
}

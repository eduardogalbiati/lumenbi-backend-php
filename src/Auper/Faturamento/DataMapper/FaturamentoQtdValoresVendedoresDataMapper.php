<?Php
namespace Auper\Faturamento\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class FaturamentoQtdValoresVendedoresDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertFaturamentoMensal(array $faturas)
    {
        $this->dropTable("Auper.dbo.faturamentoQtdValoresVendedores");
        foreach ($faturas as $line) {
            $this->insertTableArray('Auper.dbo.faturamentoQtdValoresVendedores', $line);
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
        
        $stmt = $this->db->prepare("SELECT V.idVendedor, V.nomeVendedor, SUM(VC.valor) as sumValor, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.faturamentoQtdValoresVendedores VC INNER JOIN Auper.dbo.Vendedores V on V.idVendedor = VC.idVendedor
            Where 
              datahora > dateadd(mm,-".$int.",'".$ano."-".($mes)."-01')
              AND  datahora < '".$ano."-".($mes)."-01'
            GROUP BY 
              V.idVendedor, V.nomeVendedor ");
        //$stmt->bindValue("dataIncio", $dataInicio);
        //$stmt->bindValue("dataFim", $dataFim);


        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;


    }
}

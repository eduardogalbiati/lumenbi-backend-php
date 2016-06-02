<?Php
namespace Auper\Vendas\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class VendasQtdValoresClientesVendedoresDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertVendasMensal(array $vendas)
    {
        $this->dropTable("Auper.dbo.vendasQtdValoresClientesVendedores");
        foreach ($vendas as $line) {
            $this->insertTableArray('Auper.dbo.vendasQtdValoresClientesVendedores', $line);
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
            FROM Auper.dbo.vendasQtdValoresClientes VC INNER JOIN Auper.dbo.Clientes C on C.idCliente = VC.idCliente
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

    public function loadVendedoresForCliente($idCliente, $ano, $mes)
    {
       
        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
            VC.idCliente,
            VC.idVendedor,
            V.nomeVendedor,
            SUM(VC.qtd) as qtdTotal,
            SUM(VC.valor) as valorTotal,
            MAX(VC.datahora) as ultimaData

            FROM Auper.dbo.vendasQtdValoresClientesVendedores VC 
            INNER JOIN Auper.dbo.Clientes C on C.idCliente = VC.idCliente
            INNER JOIN Auper.dbo.vendedores V on V.idVendedor = VC.idVendedor
            Where 
             VC.idCliente = :idCliente
            ".$query."
             GROUP BY VC.idCliente, VC.idVendedor, V.nomeVendedor
           ");
        $stmt->bindValue("idCliente", $idCliente);
        if($ano != false && $mes != false){
            $stmt->bindValue("ano", $ano);
            $stmt->bindValue("mes", $mes);
        }

        $stmt->execute();
        $return = $stmt->fetchAll();
        foreach ($return as $key => $value) {
            $return[$key]['valorTotal'] = (float)$return[$key]['valorTotal'];
            $return[$key]['qtdTotal'] = (int)$return[$key]['qtdTotal'];
        }
        return $return;


    }
    public function loadClientesForVendedor($idVendedor, $ano = false, $mes = false)
    {
       
        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
            VC.idCliente,
            VC.idVendedor,
            C.nomeCliente,
            SUM(VC.qtd) as qtdTotal,
            SUM(VC.valor) as valorTotal,
            MAX(VC.datahora) as ultimaData

            FROM Auper.dbo.vendasQtdValoresClientesVendedores VC 
            INNER JOIN Auper.dbo.Clientes C on C.idCliente = VC.idCliente
            INNER JOIN Auper.dbo.vendedores V on V.idVendedor = VC.idVendedor
            Where 
             VC.idVendedor = :idVendedor
            ".$query."
             GROUP BY VC.idCliente, VC.idVendedor, C.nomeCliente
           ");
        $stmt->bindValue("idVendedor", $idVendedor);
        if($ano != false && $mes != false){
            $stmt->bindValue("ano", $ano);
            $stmt->bindValue("mes", $mes);
        }

        $stmt->execute();
        $return = $stmt->fetchAll();
        foreach ($return as $key => $value) {
            $return[$key]['valorTotal'] = (float)$return[$key]['valorTotal'];
            $return[$key]['qtdTotal'] = (int)$return[$key]['qtdTotal'];
        }
        return $return;


    }
}

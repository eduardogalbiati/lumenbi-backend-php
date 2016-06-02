<?Php
namespace Auper\Vendas\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class VendasQtdValoresProdutosVendedoresDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertVendasMensal(array $vendas)
    {
        $this->dropTable("Auper.dbo.vendasQtdValoresProdutosVendedores");
        foreach ($vendas as $line) {
            $this->insertTableArray('Auper.dbo.vendasQtdValoresProdutosVendedores', $line);
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
        
        $stmt = $this->db->prepare("SELECT P.idProduto, P.nomeProduto, SUM(VC.valor) as sumValor, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresProdutos VC INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            Where 
              datahora > dateadd(mm,-".$int.",'".$ano."-".($mes)."-01')
              AND  datahora < '".$ano."-".($mes)."-01'
            GROUP BY 
              P.idProduto, P.nomeProduto ");
        //$stmt->bindValue("dataIncio", $dataInicio);
        //$stmt->bindValue("dataFim", $dataFim);


        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;


    }

    public function loadVendedoresForProduto($idProduto, $ano, $mes)
    {
       
        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
            VC.idProduto,
            VC.idVendedor,
            C.nomeVendedor,
            SUM(VC.qtd) as qtdTotal,
            SUM(VC.valor) as valorTotal,
            MAX(VC.datahora) as ultimaData

            FROM Auper.dbo.vendasQtdValoresProdutosVendedores VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.vendedores C on C.idVendedor = VC.idVendedor
            Where 
             VC.idProduto = :idProduto
            ".$query."
             GROUP BY VC.idProduto, VC.idVendedor, C.nomeVendedor
           ");
        $stmt->bindValue("idProduto", $idProduto);
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

    public function loadProdutosForVendedor($idVendedor, $ano, $mes)
    {
       
        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
            VC.idProduto,
            VC.idVendedor,
            p.nomeProduto,
            SUM(VC.qtd) as qtdTotal,
            SUM(VC.valor) as valorTotal,
            MAX(VC.datahora) as ultimaData

            FROM Auper.dbo.vendasQtdValoresProdutosVendedores VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.vendedores C on C.idVendedor = VC.idVendedor
            Where 
             VC.idVendedor = :idVendedor
            ".$query."
             GROUP BY VC.idProduto, VC.idVendedor, p.nomeProduto
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

    public function loadProdutosDistintos($idVendedor, $ano, $mes)
    {

        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
           COUNT(1) as QTD

            FROM Auper.dbo.vendasQtdValoresProdutosvendedores VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.vendedores C on C.idVendedor = VC.idVendedor
            Where 
             VC.idVendedor = :idVendedor
             ".$query."
             GROUP BY VC.idVendedor
           ");
        $stmt->bindValue("idVendedor", $idVendedor);
        if($ano != false && $mes != false){
            $stmt->bindValue("ano", $ano);
            $stmt->bindValue("mes", $mes);
        }
        $stmt->execute();
        $return = $stmt->fetchAll();
       
        return $return[0]['QTD'];
    }

     public function loadQtdVendidaForVendedor($idVendedor, $ano, $mes)
    {
        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
           SUM(qtd) as QTD

            FROM Auper.dbo.vendasQtdValoresProdutosVendedores VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.vendedores C on C.idVendedor = VC.idVendedor
            Where 
             VC.idVendedor = :idVendedor
             ".$query."
             GROUP BY VC.idVendedor
           ");
        $stmt->bindValue("idVendedor", $idVendedor);
        if($ano != false && $mes != false){
            $stmt->bindValue("ano", $ano);
            $stmt->bindValue("mes", $mes);
        }

        $stmt->execute();
        $return = $stmt->fetchAll();
       
        return (int)$return[0]['QTD'];
    }
}

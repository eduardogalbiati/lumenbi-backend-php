<?Php
namespace Auper\Vendas\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class VendasQtdValoresProdutosClientesDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertVendasMensal(array $vendas)
    {
        $this->dropTable("Auper.dbo.vendasQtdValoresProdutosClientes");
        foreach ($vendas as $line) {
            $this->insertTableArray('Auper.dbo.vendasQtdValoresProdutosClientes', $line);
        }

    }

    public function loadClientesForProduto($idProduto, $ano, $mes)
    {
       
        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
            VC.idProduto,
            VC.idCliente,
            C.nomeCliente,
            SUM(VC.qtd) as qtdTotal,
            SUM(VC.valor) as valorTotal,
            MAX(VC.datahora) as ultimaData

            FROM Auper.dbo.vendasQtdValoresProdutosClientes VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
            Where 
             VC.idProduto = :idProduto
            ".$query."
             GROUP BY VC.idProduto, VC.idCliente, C.nomeCliente
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

    public function loadProdutosForCliente($idCliente, $ano = false, $mes = false)
    {
       
        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
            VC.idProduto,
            VC.idCliente,
            P.nomeProduto,
            SUM(VC.qtd) as qtdTotal,
            SUM(VC.valor) as valorTotal,
            MAX(VC.datahora) as ultimaData

            FROM Auper.dbo.vendasQtdValoresProdutosClientes VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
            Where 
             VC.idCliente = :idCliente
            ".$query."
             GROUP BY VC.idProduto, VC.idCliente, P.nomeProduto
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

    public function loadClientesDistintos($idProduto, $ano, $mes)
    {

        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
           COUNT(1) as QTD

            FROM Auper.dbo.vendasQtdValoresProdutosClientes VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
            Where 
             VC.idProduto = :idProduto
             ".$query."
             GROUP BY VC.idProduto
           ");
        $stmt->bindValue("idProduto", $idProduto);
        if($ano != false && $mes != false){
            $stmt->bindValue("ano", $ano);
            $stmt->bindValue("mes", $mes);
        }
        $stmt->execute();
        $return = $stmt->fetchAll();
       
        return $return[0]['QTD'];
    }

    public function loadProdutosDistintos($idCliente, $ano, $mes)
    {

        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
           COUNT(1) as QTD

            FROM Auper.dbo.vendasQtdValoresProdutosClientes VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
            Where 
             VC.idCliente = :idCliente
             ".$query."
             GROUP BY VC.idCliente
           ");
        $stmt->bindValue("idCliente", $idCliente);
        if($ano != false && $mes != false){
            $stmt->bindValue("ano", $ano);
            $stmt->bindValue("mes", $mes);
        }
        $stmt->execute();
        $return = $stmt->fetchAll();
       
        return $return[0]['QTD'];
    }

    public function loadQtdVendidaForProduto($idProduto, $ano, $mes)
    {
        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
           SUM(qtd) as QTD

            FROM Auper.dbo.vendasQtdValoresProdutosClientes VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
            Where 
             VC.idProduto = :idProduto
             ".$query."
             GROUP BY VC.idProduto
           ");
        $stmt->bindValue("idProduto", $idProduto);
        if($ano != false && $mes != false){
            $stmt->bindValue("ano", $ano);
            $stmt->bindValue("mes", $mes);
        }

        $stmt->execute();
        $return = $stmt->fetchAll();
       
        return (int)$return[0]['QTD'];
    }

    public function loadQtdVendidaForCliente($idCliente, $ano, $mes)
    {
        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
           SUM(qtd) as QTD

            FROM Auper.dbo.vendasQtdValoresProdutosClientes VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
            Where 
             VC.idCliente = :idCliente
             ".$query."
             GROUP BY VC.idCliente
           ");
        $stmt->bindValue("idCliente", $idCliente);
        if($ano != false && $mes != false){
            $stmt->bindValue("ano", $ano);
            $stmt->bindValue("mes", $mes);
        }

        $stmt->execute();
        $return = $stmt->fetchAll();
       
        return (int)$return[0]['QTD'];
    }
}

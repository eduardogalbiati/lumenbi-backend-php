<?Php
namespace Auper\Compras\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class ComprasQtdValoresProdutosFornecedoresDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertVendasMensal(array $vendas)
    {
        $this->dropTable("Auper.dbo.comprasQtdValoresProdutosFornecedores");
        foreach ($vendas as $line) {
            $this->insertTableArray('Auper.dbo.comprasQtdValoresProdutosFornecedores', $line);
        }

    }

    public function loadFornecedoresForProduto($idProduto)
    {
       
        $stmt = $this->db->prepare("SELECT 
            VC.idProduto,
            VC.idFornecedor,
            C.nomeFornecedor,
            SUM(VC.qtd) as qtdTotal,
            SUM(VC.valor) as valorTotal,
            MAX(VC.datahora) as ultimaData

            FROM Auper.dbo.comprasQtdValoresProdutosFornecedores VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.fornecedores C on C.idFornecedor = VC.idFornecedor
            Where 
             VC.idProduto = :idProduto
             GROUP BY VC.idProduto, VC.idFornecedor, C.nomeFornecedor
           ");
        $stmt->bindValue("idProduto", $idProduto);

        $stmt->execute();
        $return = $stmt->fetchAll();
        foreach ($return as $key => $value) {
            $return[$key]['valorTotal'] = (float)$return[$key]['valorTotal'];
            $return[$key]['valorUnit'] = (float)($return[$key]['valorTotal']/$return[$key]['qtdTotal']);
            $return[$key]['qtdTotal'] = (int)$return[$key]['qtdTotal'];
        }
        return $return;


    }

    public function loadProdutosForFornecedor($idFornecedor)
    {
       
        $stmt = $this->db->prepare("SELECT 
            VC.idProduto,
            VC.idFornecedor,
            P.nomeProduto,
            SUM(VC.qtd) as qtdTotal,
            SUM(VC.valor) as valorTotal,
            MAX(VC.datahora) as ultimaData

            FROM Auper.dbo.comprasQtdValoresProdutosFornecedores VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.fornecedores C on C.idFornecedor = VC.idFornecedor
            Where 
             VC.idFornecedor = :idFornecedor
             GROUP BY VC.idProduto, VC.idFornecedor, P.nomeProduto
           ");
        $stmt->bindValue("idFornecedor", $idFornecedor);

        $stmt->execute();
        $return = $stmt->fetchAll();
        foreach ($return as $key => $value) {
            $return[$key]['valorTotal'] = (float)$return[$key]['valorTotal'];
            $return[$key]['valorUnit'] = (float)($return[$key]['valorTotal']/$return[$key]['qtdTotal']);
            $return[$key]['qtdTotal'] = (int)$return[$key]['qtdTotal'];
        }
        return $return;


    }

    public function loadFornecedoresDistintos($idProduto)
    {
         $stmt = $this->db->prepare("SELECT 
           COUNT(1) as QTD

            FROM Auper.dbo.comprasQtdValoresProdutosFornecedores VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.fornecedores C on C.idFornecedor = VC.idFornecedor
            Where 
             VC.idProduto = :idProduto
             GROUP BY VC.idProduto
           ");
        $stmt->bindValue("idProduto", $idProduto);

        $stmt->execute();
        $return = $stmt->fetchAll();
       
        return $return[0]['QTD'];
    }

    public function loadProdutosDistintos($idFornecedor)
    {
         $stmt = $this->db->prepare("SELECT 
           COUNT(1) as QTD

            FROM Auper.dbo.comprasQtdValoresProdutosFornecedores VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.fornecedores C on C.idFornecedor = VC.idFornecedor
            Where 
             VC.idFornecedor = :idFornecedor
             GROUP BY VC.idFornecedor
           ");
        $stmt->bindValue("idFornecedor", $idFornecedor);

        $stmt->execute();
        $return = $stmt->fetchAll();
       
        return $return[0]['QTD'];
    }

    public function loadQtdCompradaForFornecedor($idFornecedor, $ano, $mes)
    {
        if($ano != false && $mes != false){
            $query .= ' AND VC.mes = :mes';
            $query .= ' AND VC.ano = :ano';
        }
        $stmt = $this->db->prepare("SELECT 
           SUM(qtd) as QTD

            FROM Auper.dbo.comprasQtdValoresProdutosFornecedores VC 
            INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            INNER JOIN Auper.dbo.fornecedores C on C.idFornecedor = VC.idFornecedor
            Where 
             VC.idFornecedor = :idFornecedor
             ".$query."
             GROUP BY VC.idFornecedor
           ");
        $stmt->bindValue("idFornecedor", $idFornecedor);
        if($ano != false && $mes != false){
            $stmt->bindValue("ano", $ano);
            $stmt->bindValue("mes", $mes);
        }

        $stmt->execute();
        $return = $stmt->fetchAll();
       
        return (int)$return[0]['QTD'];
    }
}

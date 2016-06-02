<?Php
namespace Auper\Compras\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class comprasQtdValoresFornecedoresDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertComprasMensal(array $compras)
    {
        $this->dropTable("Auper.dbo.comprasQtdValoresFornecedores");
        foreach ($compras as $line) {
            $this->insertTableArray('Auper.dbo.comprasQtdValoresFornecedores', $line);
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
        
        $stmt = $this->db->prepare("SELECT F.idFornecedor, F.nomeFornecedor, SUM(CF.valor) as sumValor, SUM(CF.qtd) as sumQtd
            FROM Auper.dbo.comprasQtdValoresFornecedores CF INNER JOIN Auper.dbo.fornecedores F on F.idFornecedor = CF.idFornecedor
            Where 
              datahora > dateadd(mm,-".$int.",'".$ano."-".($mes)."-01')
              AND  datahora < '".$ano."-".($mes)."-01'
            GROUP BY 
              F.idFornecedor, F.nomeFornecedor ");
        //$stmt->bindValue("dataIncio", $dataInicio);
        //$stmt->bindValue("dataFim", $dataFim);


        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;


    }

    
    public function loadHistoricoValoresCompra($idProduto, $ano)
    {
         $stmt = $this->db->prepare("SELECT VC.ano, VC.mes, P.idProduto, P.nomeProduto, SUM(VC.valorCusto) as sumValor, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresProdutos VC INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            Where 
             VC.idProduto = :idProduto
             AND ano = :ano
            GROUP BY 
              VC.ano, VC.mes, P.idProduto, P.nomeProduto ");

        $stmt->bindValue("idProduto", $idProduto);
        $stmt->bindValue("ano", $ano);

        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;

    }

    public function loadHistoricoValoresVenda($idProduto, $ano)
    {
         $stmt = $this->db->prepare("SELECT VC.ano, VC.mes, P.idProduto, P.nomeProduto, SUM(VC.valor) as sumValor, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresProdutos VC INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            Where 
             VC.idProduto = :idProduto
             AND ano = :ano
            GROUP BY 
              VC.ano, VC.mes, P.idProduto, P.nomeProduto ");

        $stmt->bindValue("idProduto", $idProduto);
        $stmt->bindValue("ano", $ano);

        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;

    }

    public function loadHistoricoValoresMargem($idProduto, $ano)
    {
         $stmt = $this->db->prepare("SELECT VC.ano, VC.mes, P.idProduto, P.nomeProduto, AVG(VC.margem) as avgMargem, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresProdutos VC INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            Where 
             VC.idProduto = :idProduto
             AND ano = :ano
            GROUP BY 
              VC.ano, VC.mes, P.idProduto, P.nomeProduto ");

        $stmt->bindValue("idProduto", $idProduto);
        $stmt->bindValue("ano", $ano);

        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;

    }

    public function loadHistoricoValoresMargemDesde($mes, $ano, $int)
    {
        $date = new \DateTime($ano.'-'.$mes.'-01');
        $dateOp = new \Core\Utils\DateOperation($date);
        
       // $dateOp->subMonth(1);
        $dateOp->subMonth($int);
        $mesInt = $dateOp->getMonth();
        $anoInt = $dateOp->getYear();
        $diaInt = '01';
        $desde = $anoInt.'-'.$mesInt.'-'.$diaInt;

         $stmt = $this->db->prepare("SELECT P.idProduto, P.nomeProduto, AVG(VC.margem) as avgMargem, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresProdutos VC INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            Where 
             VC.datahora >= :datahora
            GROUP BY 
              P.idProduto, P.nomeProduto ");

        $stmt->bindValue("datahora", $desde);

        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;

    }
	

}

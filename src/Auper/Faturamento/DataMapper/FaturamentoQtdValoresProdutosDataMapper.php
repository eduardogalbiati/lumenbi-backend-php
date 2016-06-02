<?Php
namespace Auper\Faturamento\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class FaturamentoQtdValoresProdutosDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertFaturamentoMensal(array $faturas)
    {
        $this->dropTable("Auper.dbo.faturamentoQtdValoresProdutos");
        foreach ($faturas as $line) {
            $this->insertTableArray('Auper.dbo.faturamentoQtdValoresProdutos', $line);
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
            FROM Auper.dbo.faturamentoQtdValoresProdutos VC INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
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

    public function loadFaturamentoProdutos($mes, $ano, $int)
    {


        $this->intFilter($mes);
        $this->intFilter($int);
        $this->intFilter($ano);
        
        $stmt = $this->db->prepare("SELECT P.idProduto, P.nomeProduto, VC.valor, VC.qtd, VC.prcLucro, VC.valorCusto
            FROM Auper.dbo.faturamentoQtdValoresProdutos VC INNER JOIN Auper.dbo.produtos P on P.idProduto = VC.idProduto
            where ano = :ano and mes = :mes
            GROUP BY ano, mes, P.idProduto, P.nomeProduto, VC.valor, VC.qtd, VC.prcLucro, VC.valorCusto
            ");
        $stmt->bindValue("mes", $mes);
        $stmt->bindValue("ano", $ano);



        $stmt->execute();
        $return = $stmt->fetchAll();
        foreach ($return as $item) {
            $arr[$item['idProduto']] = array(
                'valorUnit' => round(($item['valor']/$item['qtd']),2),
                'valorCusto' => round(($item['valorCusto']/$item['qtd']),2),
                'prcLucro' => round($item['prcLucro'],2) * 100,
                );
        }
        return $arr;


    }
}

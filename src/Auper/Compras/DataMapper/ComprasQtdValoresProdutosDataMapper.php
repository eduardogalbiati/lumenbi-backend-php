<?Php
namespace Auper\Compras\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class ComprasQtdValoresProdutosDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertComprasMensal(array $compras)
    {
        $this->dropTable("Auper.dbo.comprasQtdValoresProdutos");
        foreach ($compras as $line) {
            $this->insertTableArray('Auper.dbo.comprasQtdValoresProdutos', $line);
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
        
        $stmt = $this->db->prepare("SELECT P.idProduto, P.nomeProduto, SUM(CP.valor) as sumValor, SUM(CP.qtd) as sumQtd
            FROM Auper.dbo.comprasQtdValoresProdutos CP INNER JOIN Auper.dbo.produtos P on P.idProduto = CP.idProduto
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
}

<?Php
namespace Auper\Vendas\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class VendasQtdValoresVendedoresDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;

    }


    public function insertVendasMensal(array $vendas)
    {
        $this->dropTable("Auper.dbo.vendasQtdValoresVendedores");
        foreach ($vendas as $line) {
            $this->insertTableArray('Auper.dbo.vendasQtdValoresVendedores', $line);
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
        
        $stmt = $this->db->prepare("SELECT C.idVendedor, C.nomeVendedor, SUM(VC.valor) as sumValor, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresVendedores VC INNER JOIN Auper.dbo.Vendedores C on C.idVendedor = VC.idVendedor
            Where 
              datahora > dateadd(mm,-".$int.",'".$ano."-".($mes)."-01')
              AND  datahora < '".$ano."-".($mes)."-01'
            GROUP BY 
              C.idVendedor, C.nomeVendedor ");
        //$stmt->bindValue("dataIncio", $dataInicio);
        //$stmt->bindValue("dataFim", $dataFim);


        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;


    }

    public function loadTopVendedoresAno($ano)
    {
        $stmt = $this->db->prepare("SELECT TOP 10 SUM(vv.valor) as total,  vv.idVendedor, v.nomeVendedor
            FROM Auper.dbo.vendasQtdValoresVendedores vv
            INNER JOIN Auper.dbo.vendedores v
            ON v.idVendedor = vv.idVendedor
             WHERE vv.ano=:ano GROUP BY vv.idVendedor, v.nomeVendedor  ORDER BY SUM(vv.valor) desc");

        $stmt->bindValue("ano", $ano);

        $stmt->execute();
        $return = $stmt->fetchAll();

        //calculando o total
        foreach ($return as $element) {
            $sum += $element['total'];
        }
        //adicionando as porcentagens
        $item = 0;
        foreach ($return as $element) {
            $item++;
            unset($arr);
            $arr['nome'] = (($element['nomeVendedor'] == '')?'Sem vendedor':$element['nomeVendedor']);
            $arr['total']= $sum;
            $arr['parcial'] = $element['total'];
            $arr['prc'] = round(($element['total'] / $sum)*100);
            $arr['pos'] = $item;
            $final[] = $arr;

            unset($arrChart);
            $arrChart['label'] = (($element['nomeVendedor'] == '')?'Sem vendedor':$element['nomeVendedor']);
            $arrChart['data'] = $arr['prc'];

            $finalChart[] = $arrChart;

        }
        $return['list'] = $final;
        $return['chart'] = $finalChart;
        return $return;
    }

    public function loadTopVendedoresMes($mes, $ano)
    {
        $stmt = $this->db->prepare("SELECT TOP 10 SUM(vv.valor) as total,  vv.idVendedor, v.nomeVendedor
            FROM Auper.dbo.vendasQtdValoresVendedores vv
            INNER JOIN Auper.dbo.vendedores v
            ON v.idVendedor = vv.idVendedor 
            WHERE vv.ano=:ano AND vv.mes=:mes GROUP BY vv.idVendedor, v.nomeVendedor  ORDER BY SUM(vv.valor) desc");

        $stmt->bindValue("ano", $ano);
        $stmt->bindValue("mes", $mes);

        $stmt->execute();
        $return = $stmt->fetchAll();

        //calculando o total
        foreach ($return as $element) {
            $sum += $element['total'];
        }
        //adicionando as porcentagens
        $item = 0;
        foreach ($return as $element) {
            $item++;
            unset($arr);
            $arr['nome'] = (($element['nomeVendedor'] == '')?'Sem vendedor':$element['nomeVendedor']);
            $arr['total']= $sum;
            $arr['parcial'] = $element['total'];
            $arr['prc'] = round(($element['total'] / $sum)*100);
            $arr['pos'] = $item;
            $final[] = $arr;

            unset($arrChart);
            $arrChart['label'] = (($element['nomeVendedor'] == '')?'Sem vendedor':$element['nomeVendedor']);
            $arrChart['data'] = $arr['prc'];

            $finalChart[] = $arrChart;

        }
        $return['list'] = $final;
        $return['chart'] = $finalChart;
        return $return;
    }

    public function loadTopVendedoresTotal()
    {
        $stmt = $this->db->prepare("SELECT TOP 10 SUM(vv.valor) as total,  vv.idVendedor, v.nomeVendedor
            FROM Auper.dbo.vendasQtdValoresVendedores vv
            INNER JOIN Auper.dbo.vendedores v
            ON v.idVendedor = vv.idVendedor
            GROUP BY vv.idVendedor, v.nomeVendedor  ORDER BY SUM(vv.valor) desc");
        $stmt->execute();
        $return = $stmt->fetchAll();

        //calculando o total
        foreach ($return as $element) {
            $sum += $element['total'];
        }
        //adicionando as porcentagens
        $item = 0;
        foreach ($return as $element) {
            $item++;
            unset($arr);
            $arr['nome'] = (($element['nomeVendedor'] == '')?'Sem vendedor':$element['nomeVendedor']);
            $arr['total']= $sum;
            $arr['parcial'] = $element['total'];
            $arr['prc'] = round(($element['total'] / $sum)*100);
            $arr['pos'] = $item;
            $final[] = $arr;

            unset($arrChart);
            $arrChart['label'] = (($element['nomeVendedor'] == '')?'Sem vendedor':$element['nomeVendedor']);
            $arrChart['data'] = $arr['prc'];

            $finalChart[] = $arrChart;

        }
        $return['list'] = $final;
        $return['chart'] = $finalChart;
        return $return;
    }

    public function loadHistoricoValoresVenda($idVendedor, $ano)
    {
         $stmt = $this->db->prepare("SELECT VC.ano, VC.mes, P.idVendedor, P.nomeVendedor, SUM(VC.valor) as sumValor, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresvendedores VC INNER JOIN Auper.dbo.vendedores P on P.idVendedor = VC.idVendedor
            Where 
             VC.idVendedor = :idVendedor
             AND ano = :ano
            GROUP BY 
              VC.ano, VC.mes, P.idVendedor, P.nomeVendedor ");

        $stmt->bindValue("idVendedor", $idVendedor);
        $stmt->bindValue("ano", $ano);

        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;

    }

    public function loadHistoricoValoresMargem($idVendedor, $ano)
    {
         $stmt = $this->db->prepare("SELECT VC.ano, VC.mes, P.idVendedor, P.nomeVendedor, AVG(VC.margem) as avgMargem, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresvendedores VC INNER JOIN Auper.dbo.vendedores P on P.idVendedor = VC.idVendedor
            Where 
             VC.idVendedor = :idVendedor
             AND ano = :ano
            GROUP BY 
              VC.ano, VC.mes, P.idVendedor, P.nomeVendedor ");

        $stmt->bindValue("idVendedor", $idVendedor);
        $stmt->bindValue("ano", $ano);

        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;

    }
}

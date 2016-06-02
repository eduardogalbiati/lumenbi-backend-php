<?Php
namespace Auper\Vendas\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class VendasQtdValoresClientesDataMapper extends AbstractDataMapper
{
    protected $db;
    protected $newArray = array();

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function insertVendasMensal(array $vendas)
    {
        $this->dropTable("Auper.dbo.vendasQtdValoresClientes");
        foreach ($vendas as $line) {
            $this->insertTableArray('Auper.dbo.vendasQtdValoresClientes', $line);
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
            FROM Auper.dbo.vendasQtdValoresClientes VC INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
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
	

    public function loadTopClientesAno($ano)
    {
        $stmt = $this->db->prepare("SELECT TOP 10 SUM(vc.valor) as total,  vc.idCliente, c.nomeCliente
            FROM Auper.dbo.vendasQtdValoresClientes vc
            INNER JOIN Auper.dbo.clientes c
            ON c.idCliente = vc.idCliente
             WHERE vc.ano=:ano GROUP BY vc.idCliente, c.nomeCliente  ORDER BY SUM(vc.valor) desc");

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
            $arr['nome'] = (($element['nomeCliente'] == '')?'Sem vendedor':$element['nomeCliente']);
            $arr['total']= $sum;
            $arr['parcial'] = $element['total'];
            $arr['prc'] = round(($element['total'] / $sum)*100);
            $arr['pos'] = $item;
            $final[] = $arr;

            unset($arrChart);
            $arrChart['label'] = (($element['nomeCliente'] == '')?'Sem vendedor':$element['nomeCliente']);
            $arrChart['data'] = $arr['prc'];

            $finalChart[] = $arrChart;

        }
        $return['list'] = $final;
        $return['chart'] = $finalChart;
        return $return;
    }

    public function loadTopClientesMes($mes, $ano)
    {
        $stmt = $this->db->prepare("SELECT TOP 10 SUM(vc.valor) as total,  vc.idCliente, c.nomeCliente
            FROM Auper.dbo.vendasQtdValoresClientes vc
            INNER JOIN Auper.dbo.clientes c
            ON c.idCliente = vc.idCliente 
            WHERE vc.ano=:ano AND vc.mes=:mes GROUP BY vc.idCliente, c.nomeCliente  ORDER BY SUM(vc.valor) desc");

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
            $arr['nome'] = (($element['nomeCliente'] == '')?'Sem vendedor':$element['nomeCliente']);
            $arr['total']= $sum;
            $arr['parcial'] = $element['total'];
            $arr['prc'] = round(($element['total'] / $sum)*100);
            $arr['pos'] = $item;
            $final[] = $arr;

            unset($arrChart);
            $arrChart['label'] = (($element['nomeCliente'] == '')?'Sem vendedor':$element['nomeCliente']);
            $arrChart['data'] = $arr['prc'];

            $finalChart[] = $arrChart;

        }
        $return['list'] = $final;
        $return['chart'] = $finalChart;
        return $return;
    }

    public function loadTopClientesTotal()
    {
        $stmt = $this->db->prepare("SELECT TOP 10 SUM(vc.valor) as total,  vc.idCliente, c.nomeCliente
            FROM Auper.dbo.vendasQtdValoresClientes vc
            INNER JOIN Auper.dbo.clientes c
            ON c.idCliente = vc.idCliente
            GROUP BY vc.idCliente, c.nomeCliente  ORDER BY SUM(vc.valor) desc");
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
            $arr['nome'] = (($element['nomeCliente'] == '')?'Sem vendedor':$element['nomeCliente']);
            $arr['total']= $sum;
            $arr['parcial'] = $element['total'];
            $arr['prc'] = round(($element['total'] / $sum)*100);
            $arr['pos'] = $item;
            $final[] = $arr;

            unset($arrChart);
            $arrChart['label'] = (($element['nomeCliente'] == '')?'Sem vendedor':$element['nomeCliente']);
            $arrChart['data'] = $arr['prc'];

            $finalChart[] = $arrChart;

        }
        $return['list'] = $final;
        $return['chart'] = $finalChart;
        return $return;
    }

    public function loadHistoricoValoresCompra($idCliente, $ano)
    {
         $stmt = $this->db->prepare("SELECT VC.ano, VC.mes, C.idCliente, C.nomeCliente, SUM(VC.valorCusto) as sumValor, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresClientes VC INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
            Where 
             VC.idCliente = :idCliente
             AND ano = :ano
            GROUP BY 
              VC.ano, VC.mes, C.idCliente, C.nomeCliente ");

        $stmt->bindValue("idCliente", $idCliente);
        $stmt->bindValue("ano", $ano);

        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;

    }

    public function loadHistoricoValoresVenda($idCliente, $ano)
    {
         $stmt = $this->db->prepare("SELECT VC.ano, VC.mes, C.idCliente, C.nomeCliente, SUM(VC.valor) as sumValor, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresClientes VC INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
            Where 
             VC.idCliente = :idCliente
             AND ano = :ano
            GROUP BY 
              VC.ano, VC.mes, C.idCliente, C.nomeCliente ");

        $stmt->bindValue("idCliente", $idCliente);
        $stmt->bindValue("ano", $ano);

        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;

    }

    public function loadHistoricoValoresMargem($idCliente, $ano)
    {
         $stmt = $this->db->prepare("SELECT VC.ano, VC.mes, C.idCliente, C.nomeCliente, AVG(VC.margem) as avgMargem, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresClientes VC INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
            Where 
             VC.idCliente = :idCliente
             AND ano = :ano
            GROUP BY 
              VC.ano, VC.mes, C.idCliente, C.nomeCliente ");

        $stmt->bindValue("idCliente", $idCliente);
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

         $stmt = $this->db->prepare("SELECT C.idCliente, C.nomeCliente, AVG(VC.margem) as avgMargem, SUM(VC.qtd) as sumQtd
            FROM Auper.dbo.vendasQtdValoresClientes VC INNER JOIN Auper.dbo.clientes C on C.idCliente = VC.idCliente
            Where 
             VC.datahora >= :datahora
            GROUP BY 
              C.idCliente, C.nomeCliente ");

        $stmt->bindValue("datahora", $desde);

        $stmt->execute();
        $return = $stmt->fetchAll();

        return $return;

    }

}

<?Php
namespace Auper\Faturamento\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\Services\ClientesExcluidosService;

class FaturamentoDataMapper
{
    protected $db;
    protected $cliService;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function carregaFaturamentoMensal(\DateTime $dataInicio, \DateTime $dataFim)
    {

      $query = "SELECT Prod.grupo, 
       Pi.vlrtotal,
       Pi.VlrUnit,
       Pi.Qtde, 
       Ped.dataref, 
       Ped.codcli AS idCliente, 
       Pi.cdvend  AS idVendedor,
       Pi.calitem as idProduto,
       Ped.Chave  AS idPedido,
       Pi.custo,
       Pi.Descom
FROM   [Industrial].[dbo].[pedite] Pi 
       INNER JOIN [Industrial].[dbo].pedido Ped 
               ON Ped.nrodoc = Pi.nrodoc 
       LEFT JOIN [Industrial].[dbo].produtos Prod 
              ON Prod.codigo = Pi.calitem 
       LEFT JOIN [Industrial].[dbo].clientes C 
              ON C.codigo = Ped.codcli 
       LEFT JOIN [Industrial].[dbo].vendedor V 
              ON V.codigo = Pi.cdvend 
       LEFT JOIN [Industrial].[dbo].natoper N 
              ON N.chave = Pi.natoper 
WHERE  N.tipo = 'VE' 
AND Pi.dataref > '2015-05-01' 
       AND Pi.dataref < '2016-01-01' 
";
/*
      
AND Pi.dataref > '2015-05-01' 
       AND Pi.dataref < '2016-01-01' 
*/

      $stmt = $this->db->prepare($query);

        //$stmt->bindValue("idDepartamento",$idDepartamento);
        $stmt->execute();
        //$stmt->setFetchMode(\PDO::FETCH_CLASS , 'DepartmentEntity');
        if (!$faturas = $stmt->fetchAll()) {
            throw new \Exception('Nenhuma venda lançada para o período');
        }


        return $faturas;
    }
}

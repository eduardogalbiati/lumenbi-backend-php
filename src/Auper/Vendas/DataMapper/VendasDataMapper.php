<?Php
namespace Auper\Vendas\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\Services\ClientesExcluidosService;

class VendasDataMapper
{
    protected $db;
    protected $cliService;

    public function __construct(Connection $db, ClientesExcluidosService $cliService)
    {
        $this->db = $db;
        $this->cliService = $cliService;
    }

    public function carregaVendasMensal(\DateTime $dataInicio, \DateTime $dataFim)
    {

      $notIn = $this->cliService->getNotInQuery($field = 'Ped.codcli');

      $query = "SELECT Prod.grupo, 
       Vendas.total AS vlrtotal,
       Vi.vrl_un as VlrUnit,
       Prod.lucro,
       Vi.QTD as Qtde, 
       Vi.ITEM_CODIGO as idProduto,
       Vendas.DIA as dataref, 
       Prod.Custo,
       Vendas.CLIENTE AS idCliente, 
       C.nome     AS nomeCliente, 
       Vendas.OPERADOR  AS idVendedor,
       Vendas.Codigo  AS idPedido,
       Vi.desconto as Descom,
       Vi.desconto as VlrDesc
FROM   [shoficina].[dbo].[ITENS_VENDA] Vi 
       INNER JOIN [shoficina].[dbo].VENDAS Vendas 
               ON Vendas.CODIGO = Vi.VENDA 
       LEFT JOIN [shoficina].[dbo].itens Prod 
              ON Prod.codigo = Vi.ITEM_CODIGO 
       LEFT JOIN [shoficina].[dbo].clientes C 
              ON C.codigo = Vendas.cliente 
       LEFT JOIN [shoficina].[dbo].usuarios V 
              ON V.codigo = Vendas.OPERADOR 

      
";
/*
 AND Vendas.dataref > '2012-09-01 00:00:00' 
      AND Vendas.dataref = '2015-08-01 00:00:00' 
--
*/
      if($notIn){
        $query .= ' AND '.$notIn;
      }
      $stmt = $this->db->prepare($query);

        //$stmt->bindValue("idDepartamento",$idDepartamento);
        $stmt->execute();
        //$stmt->setFetchMode(\PDO::FETCH_CLASS , 'DepartmentEntity');
        if (!$vendas = $stmt->fetchAll()) {
            throw new \Exception('Nenhuma venda lançada para o período');
        }


        return $vendas;
    }
}

<?Php
namespace Auper\Compras\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\Services\FornecedoresExcluidosService;

class ComprasDataMapper
{
    protected $db;
    protected $forService;

    public function __construct(Connection $db, FornecedoresExcluidosService $forService)
    {
        $this->db = $db;
        $this->forService = $forService;
    }

    public function carregaComprasMensal(\DateTime $dataInicio, \DateTime $dataFim)
    {

      $notIn = $this->forService->getNotInQuery($field = 'Ei.fornece');

      $query = "SELECT E.chave    AS idCompra, 
       E.icmsub, 
       E.pdesc, 
       E.vlrfrete, 
       E.vlroutros, 
       Ei.total, 
       Ei.vlripi, 
       Ei.valoricmsst, 
       Ei.valoricms,
       Ei.unitario, 
       Ei.qtde, 
       Ei.materia AS idProduto, 
       Ei.fornece AS idFornecedor, 
       E.emissao,
       E.tipo
FROM   [Industrial].[dbo].[entradaite] Ei 
       INNER JOIN [Industrial].[dbo].entrada E 
               ON E.nrodoc = Ei.nrodoc 
       LEFT JOIN [Industrial].[dbo].natoper N 
              ON N.chave = E.natoper 
WHERE  N.tipo = 'CO'";
/*
      AND E.emissao = '2015-07-31'

*/
      if($notIn){
        $query .= ' AND '.$notIn;
      }
      $stmt = $this->db->prepare($query);

        //$stmt->bindValue("idDepartamento",$idDepartamento);
        $stmt->execute();
        //$stmt->setFetchMode(\PDO::FETCH_CLASS , 'DepartmentEntity');
        if (!$compras = $stmt->fetchAll()) {
            throw new \Exception('Nenhuma venda lançada para o período');
        }


        return $compras;
    }
}

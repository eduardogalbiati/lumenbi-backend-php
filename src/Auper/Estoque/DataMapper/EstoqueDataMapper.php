<?Php
namespace Auper\Estoque\DataMapper;

use Doctrine\DBAL\Connection;

class EstoqueDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function carregaEstoqueAtual()
    {

      $query = "SELECT 
              I.ESTOQUE_DISP,
              I.CUSTO,
              I.VENDA,
              I.NC_MERCOSUL,
              I.NOME
      FROM   [shoficina].[dbo].[ITENS] I 
      WHERE I.ESTOQUE_DISP > 0 ";

     
      $stmt = $this->db->prepare($query);

        //$stmt->bindValue("idDepartamento",$idDepartamento);
        $stmt->execute();
        //$stmt->setFetchMode(\PDO::FETCH_CLASS , 'DepartmentEntity');
        if (!$itensEstoque = $stmt->fetchAll()) {
            throw new \Exception('Nenhum item encontrado no estoque');
        }


        return $itensEstoque;
    }
}

<?Php
namespace Auper\Produtos\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class ExternalProdutosDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function loadProdutos()
    {
     
        $stmt = $this->db->prepare("SELECT [codigo], 
                nome as descricao, 
                ultima_compra as dtcad,
                ultima_venda as ultcompra
            FROM   [shoficina].[dbo].[Itens]
            WHERE codigo NOT IN (SELECT idProduto as codigo FROM [Auper].[dbo].[Produtos] )
  ");

        $stmt->execute();

        if (!$clientes = $stmt->fetchAll()) {
           // throw new \Exception('Nenhuma venda lançada para o período');
        }

        return $clientes;
    }

     public function loadProdutosForUpdate($dtInicio)
    {
     /*
        $stmt = $this->db->prepare("SELECT [codigo], 
                [nome], 
                [fantasia],
                [dtinc], 
                [endereco], 
                [cep], 
                [bairro], 
                [cidade], 
                [uf], 
                [fone], 
                [ultcompra],
                [motivobloqueio1] ,
                [ativo],
                [status]
            FROM   [Industrial].[dbo].[Clientes] 
            WHERE DataAlteracao >= :dtInicio
  ");
        $stmt->bindValue("dtInicio",$dtInicio);
        $stmt->execute();

        if (!$clientes = $stmt->fetchAll()) {
           // throw new \Exception('Nenhuma venda lançada para o período');
        }

        return $clientes;
        */
    }

}

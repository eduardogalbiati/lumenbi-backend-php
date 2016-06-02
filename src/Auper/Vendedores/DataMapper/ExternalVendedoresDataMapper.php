<?Php
namespace Auper\Vendedores\DataMapper;

use Doctrine\DBAL\Connection;
use Core\Utils\DataMapper\AbstractDataMapper;

class ExternalVendedoresDataMapper extends AbstractDataMapper
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function loadVendedores()
    {
     
        $stmt = $this->db->prepare("SELECT [codigo], 
                [nome]
               
            FROM   [shoficina].[dbo].[usuarios] 
            WHERE codigo NOT IN (SELECT idVendedor as codigo FROM [Auper].[dbo].[vendedores])
  ");

        $stmt->execute();

        if (!$vendedores = $stmt->fetchAll()) {
           // throw new \Exception('Nenhuma venda lançada para o período');
        }

        return $vendedores;
    }

     public function loadVendedoresForUpdate($dtInicio)
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

<?Php

namespace Bpms\Teste\Entity;


class UsuariosEntity
{
	private $id;
	private $nome;

	public function getNome()
	{
		return $this->nome;
	}
	public function setNome($nome)
	{
		$this->nome = $nome;
		return $this;
	}
	public function getId()
	{
		return $this->id;
	}
	
	
}
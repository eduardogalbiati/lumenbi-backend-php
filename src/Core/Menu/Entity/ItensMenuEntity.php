<?Php

namespace Core\Menu\Entity;

class ItensMenuEntity
{
	private $itens;

	function getItens()
	{
		return $this->itens;
	}

	function setItem($item)
	{
		$this->itens[] = $item;
	}

}
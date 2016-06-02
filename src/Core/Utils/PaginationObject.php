<?php

namespace Core\Utils;


class PaginationObject
{
	public $actualPage;
	public $regsPerPage;
	public $totalRegs;

	public $first;
	public $before;
	public $next;
	public $last;

	public $itens;

	public $de;
	public $ate;
	public $lastpag;

	public function __construct($actualPage, $totalRegs, $regsPerPage='10')
	{
		$this->actualPage = $actualPage;
		$this->regsPerPage = $regsPerPage;
		$this->totalRegs = $totalRegs;

		$this->buildInfo();
		$this->buildFirstBefore();
		$this->buildNextLast();
		$this->buildPagination();
	}

	private function getBottomLimit()
	{
		return  $this->actualPage - 5;
	}
    
    private function getTopLimit()
	{
		return  $this->actualPage + 5;
	}

	private function addItem($item)
	{
		$this->itens[] = $item;
	}

	private function buildFirstBefore()
	{
		$this->first  = (($this->actualPage != 1)?'enabled':'');
        $this->before = (($this->actualPage != 1)?'enabled':'');
	}

	private function buildNextLast()
	{
		$this->next = (( $this->totalRegs >= ($this->actualPage *$this->regsPerPage ) )?'enabled':'');
        $this->last = (( $this->totalRegs >= ($this->actualPage *$this->regsPerPage ) )?'enabled':'');
	}

	private function buildInfo()
	{
		$this->de = ($this->actualPage)*$this->regsPerPage - ($this->regsPerPage-1);
        $this->ate = ( ($this->actualPage*$this->regsPerPage > $this->totalRegs)? $this->totalRegs : ($this->actualPage*$this->regsPerPage) );
        $this->lastpag = (int) (($this->totalRegs / $this->regsPerPage)+1);

	}

    private function buildPagination()
    {
  
        $bottomLimit = $this->getBottomLimit();
        $topLimit = $this->getTopLimit();

        for($i = $bottomLimit;$i <= $topLimit;$i++){
            if($i > 0){
            	$pag = array();
                $pag['active']=(($i == $this->actualPage)?'1':'0');
                $pag['disabled']=((($i-1) *$this->regsPerPage >= $this->totalRegs )?'1':'0');
                $pag['num'] = $i;
                $this->addItem($pag);
            }
        }

    }

}
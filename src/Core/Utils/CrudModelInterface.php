<?Php

namespace Core\Utils;

interface CrudModelInterface
{
	public function loadEditViewInfo();
	public function loadListViewInfo();
	public function loadListActionInfo();
}
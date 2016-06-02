<?php

namespace Core\Utils\DataMapper;

use Core\Utils\Entities\AbstractEntity;
use Symfony\Component\HttpFoundation\Request;

interface DataMapperInterface
{
	public function insert(AbstractEntity $entity);
	public function update(AbstractEntity $entity,$id);
	public function loadById($id);
	public function getCount();
	public function getLimitWithFilters($page, Request $request, $regsPerPage);
}
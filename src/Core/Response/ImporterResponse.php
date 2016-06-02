<?php

namespace Core\Response;

class ImporterResponse
{
	protected $data;
	protected $status;

	public function __construct()
	{
		$this->status = 1;
	}

	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function getResponse()
	{
		return array(
			'data' => $this->data,
			'status' => $this->status
			);
	}
}
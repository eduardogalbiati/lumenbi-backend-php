<?php

namespace Core\Utils;

class DateOperation {
	
	protected $month;
	protected $year;


	public function __construct(\DateTime $datetime)
	{
		$this->month = $datetime->format("m");
		$this->year = $datetime->format("Y");
	}

	public function addMonth($month = 1)
	{
		for($count = 1; $count <= $month; $count++){
			if($this->month == 12){
				$this->year += 1;
				$this->month = 1;
			}else{
				$this->month += 1;
			}
		}
	}


	public function subMonth($month = 1)
	{
		for($count = 1; $count <= $month; $count++){
			if($this->month == 1){
				$this->year -= 1;
				$this->month = 12;
			}else{
				$this->month -= 1;
			}
		}
	}

	public function getMonth()
	{
		return (int) $this->month;
	}

	public function getYear()
	{
		return $this->year;
	}


}
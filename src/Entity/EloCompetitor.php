<?php

namespace App\Entity;

/**
*	a participant in an Elo Rating system
*/
class EloCompetitor
{
	private $id;
	private $name;
	private $rating;

	public function __construct ($id, $name, $rating)
	{
		$this->id = $id;
		$this->name = $name;
		$this->rating = $rating;
	}

	public function getId ()
	{
		return $this->id;
	}
	
	public function getName ()
	{
		return $this->name;
	}

	public function getRating ()
	{
		return $this->rating;
	}

	public function adjustRating ($adjustment)
	{
		$this->rating += $adjustment;
	}

	public function __toString ()
	{
		return $this->name.' ('.$this->id . ') : ' . $this->rating;
	}
}

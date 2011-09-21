<?php

class PhpMongEmbed extends PhpMong
{
	public $embeded;
	
	public function __construct()
	{
		$this->set( array( "collection" => "images" ) );
		parent::__construct($className=__CLASS__);
	}	
}
?>
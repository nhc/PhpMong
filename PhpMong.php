<?php

	$settings = array(
		'connectionString'  => 'mongodb://localhost',
		'dbName'            => 'test',
	);
	

class PhpMong{
	
	private $callername; // name of the calling / child class
	private $collection;
	private $conn;
	private $db;
	private $dbname = 'test';
	//public $props;
	
	public function __construct($name)
	{
		$this->callername = $name;
		
		/*
		* Connection 
		*/
		$this->conn();
		
		/*
		* Connect to collection
		*/
		
		$this->selectCollection();
	}
	
	private function conn()
	{
		$this->conn = new Mongo('mongodb://localhost'); // connect
		$this->db = $this->conn->{$this->dbname};
	}
	
	public function selectCollection()
	{
		$this->collection = $this->db->{$this->collection};
	}
	
	/*
	*	Saves attributes to the database
	*	Checks allowed fieldnames first
	*/
	
	public function save()
	{
		if( is_array( $this->fieldnames() ) ):
			foreach( $this->fieldnames() as $k => $v ):
				if( isset( $this->{$v} ) )
					$arr[$v] = $this->{$v};
			endforeach;
		endif;
		
		$this->collection->insert($arr);
		
	}
	
	/*
	*	Return one document 
	*/
	
	public function findOne($array)
	{
		$cursor = $this->collection->findOne($array);
		$this->set(array('results'=>$cursor));
		$this->close();
	}
	
	/*
	*	Embed an object withing another
	*/
	public function embedObject()
	{
		
	}
	
	public function close()
	{
		$this->conn->close();
	}
	
	public function set($array)
	{
		if( is_array( $array ) ):
			foreach( $array as $k => $v ):
				$this->{$k} = $v;
			endforeach;
		endif;
	}
	
	public function __set__($name, $value) 
	{
		if( is_array( $this->fieldnames() ) ):
			foreach( $this->fieldnames() as $k => $v ):
				if( $v == $name ) 
					$this->{$name} = $value;
				else 
					echo("Can't add this property: " . $name . "<br />"); 
			endforeach;
		endif;
    
  }

}	

class Theproduct extends PhpMong
{
	public function __construct()
	{
		$this->set( array( "collection" => "products" ) );
		parent::__construct($className=__CLASS__);
	}	
	
	public function fieldnames()
	{
		return array(
			'name', 'price', 'images'
		);
	}
	
	public function embeddedCollections()
	{
		return array(
			'images' => 'Theimage'
		);
	}
	
}

class Theimage extends PhpMong
{
	public function __construct()
	{
		$this->set( array( "collection" => "images" ) );
		parent::__construct($className=__CLASS__);
	}	
	
	public function fieldnames()
	{
		return array(
			'name', 'server'
		);
	}
	
	
}


	$m = new Theproduct();
	$m->findOne(array('name'=>'hello neil'));
	
	$m->name = 'hello neil';
	$m->images = array(
		array( 'name' => 'title3.jpg', 'server' => 'sd3' ),
		array( 'name' => 'title4.jpg', 'server' => 'dsev' )
	);
	$m->save();
	
	//print '<pre>';
	print_r($m->results['name']);
	
	
	//$m->find(array('name'=>'neil'));
	
?>
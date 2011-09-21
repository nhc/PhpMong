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
	
	private $update = false; // set with mongodb _id
	
	private $fieldNames = array();
	
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
		$this->addDefaultFieldNames();
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
	
	public function addDefaultFieldNames()
	{
		foreach( $this->fieldnames() as $k => $v ):
			$this->fieldNames[$k] = $v;
		endforeach;
		
		$this->fieldNames['created_at'] = '';
		$this->fieldNames['updated_at'] = '';
	}
	
	public function mkSaveData()
	{
		if( is_array( $this->fieldNames ) ):
				foreach( $this->fieldNames as $k => $v ):
					if( isset( $this->{$k} ) ):
						$arr[$k] = $this->{$k};
					elseif( ( $k == 'created_at' ) or ( $k == 'updated_at' ) ):
						$arr[$k] = new MongoDate();
					endif;
				endforeach;
		endif;
		
	
		return $arr;
	}
	
	
	/*
	*	Saves
	*/
	
	public function save()
	{
		if( $this->update ):
				$arr = $this->mkSaveData();
				$new = array('$set' => $arr );
				$this->collection->update(array( '_id'=> new MongoID($this->update)),$new);
				$this->set(array('results'=>$arr));
		else:
			$arr = $this->mkSaveData();
			$this->doSave($arr);
		endif;
	}
	
	public function doSave($arr)
	{
		$this->collection->save($arr,array('safe'=>true));
		if( isset($arr['_id']) ):
			$this->setMongoID($arr);
			$this->close();
		endif;
	}
	
	
	private function setMongoID($arr)
	{
		$id = (array) $arr['_id'];
		unset( $arr['_id'] );
		$arr['_id'] = $id[key($id)];
		$this->set(array('results'=>$arr));
		$this->update = $arr['_id'];
	}
	/*
	*	Return one document 
	*/
	
	public function findOne($array)
	{
		$arr = $this->collection->findOne($array);
		$this->set(array('results'=>$arr));
		$this->setMongoID($arr);
		$this->close();
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
	
}	

class Theproduct extends PhpMong
{
	public function __construct()
	{
		$this->set( array( "collection" => "products" ) );
		parent::__construct($className=__CLASS__);
	}	
	
	/*
	*	Defines our valid fields and their datatypes
	* This is where we define embedded collections too
	*/
	
	public function fieldnames()
	{
		return array(
			'name' => 'text', 
			'price' => 'text', 
			'test' => 'text',
			'images' => 'collection'
		);
	}
	
}

?>
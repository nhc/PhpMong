## phpmong

THIS IS CURRENTLY WORK IN PROGRESS AND IS NOT READY FOR USE

Simple wrapper for php mongo db. Most of the classes I found were overly complex. I only need to do a few really simple things. 

At the heart of this class is

1. Collections. CRUD functionality for storing documents.
2. Embedded collections. CRUD functionality for documents within documents.
3. Finding stuff. Mongodb has a powerful yet simple querying DSL. I wanted to keep this native.
4. Partial and atomic updates. 

### Usage

The following code will search for an embedded collection and add a new item to the embedded collection.

    $product = new Product();
    $product->find(array('images.name'=>'juicy.jpg')); // search an embedded collection with dot notation
    $product->add('images', array( 'name' => 'pear.jpg', 'server' => 'sd3' ) );
    $product->save();

Updates after save

This will only create a new product by saving it, but then will update the saved price. Represents one collection only in MongoDB

    $product = new Product();
		$m->name = 'Green Hat';
		$m->price = '12.99';
		$m->images = array(
		  array( 'name' => 'image1.jpg', 'server' => 'sd3' ),
		  array( 'name' => 'image2.jpg', 'server' => 'dsev')
	  );
	  $m->save();
	  $m->price = "16.99";
	  $m->save();

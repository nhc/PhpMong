## phpmong

Simple wrapper for php mongo db. Most of the classes I found were overly complex. I only need to do a few really simple things. 

At the heart of this class is

1. Collections. CRUD functionality for storing documents.
2. Embedded collections. CRUD functionality for documents within documents.
3. Finding stuff. Mongodb has a powerful yet simple querying DSL. I wanted to keep this native.
4. Partial updates. 

### Usage

#### Simple Docs

The following code will search for an embedded collection and add a new item to the embedded collection.

    $product = new Product();
    $product->find(array('images.name'=>'juicy.jpg')); // search an embedded collection with dot notation
    $product->add->('images', array( 'name' => 'pear.jpg', 'server' => 'sd3' ) );
    $product->save();




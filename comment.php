<?php
$id = $_POST['article_id'];

try{
    $mongodb = new MongoClient();
    $collection = $mongodb->myblogsite->articles;
}catch (MongoConnectionException $e){
    die('Failed to connect to MongoDB '.$e->getMessage());
}
$article = $collection->findOne(array('_id' => new MongoId($id)));
$comment = array(
    'name' => $_POST['commenter_name'],
    'email' => $_POST['commenter_email'],
    'comment' => $_POST['comment'],
    'posted_at' => new MongoDate()
);
$collection->update(array('_id' => new MongoId($id)),
    array('$push' => array('comments' => $comment)));
header('Location: blog.php?id='.$id);
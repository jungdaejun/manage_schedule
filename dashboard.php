<?php
try{
    $mongodb = new MongoClient();
    $articleCollection = $mongodb->myblogsite->articles;
}catch (MongoConnectionException $e){
    die('Failed to connect to MongoDB '.$e->getMessage());
}
$currentPage = (isset($_GET['page'])) ? (int) $_GET['page']:1;
$articlesPerPage = 5;
$skip = ($currentPage - 1) * $articlesPerPage;
$cursor = $articleCollection->find(array(),$fields=array('title', 'saved_at'));
$totalArticles = $cursor->count();
$totalPages = (int) ceil($totalArticles / $articlesPerPage);
$cursor->sort(array('_id'=>-1))->skip($skip)->limit($articlesPerPage);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css"/>
    <style type="text/css" media="screen">
        body{ font-size: 13px;}
        div#contentarea{width: 650px;}
    </style>
    <script type="text/javascript" charset="utf-8">
        function confirmDelete(articleId){
            var deleteArticle = confirm('Are you sere you want to delete this article?');
            if(deleteArticle){
                window.location.href = 'delete.php?id='+articleId;
            }
            return;
        }
    </script>
</head>
<body>
<div id="contentarea">
    <div id="innercontentarea">
        <h1>schedule management</h1>
        <table class="articles" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
                <th width="50%">Schedule</th>
                <th width="24%">Created at</th>
                <th width="*">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php while($cursor->hasNext()):$article = $cursor->getNext(); ?>
            <tr>
                <td>
                    <?php echo substr($article['title'], 0, 35).'....'; ?>
                </td>
                <td>
                    <?php print date('g:i a, F j', $article['saved_at']->sec); ?>
                </td>
                <td>
                    <a href="blog.php?id=<?php echo $article['_id']; ?>">
                        View
                    </a>
                    | <a href="edit.php?id=<?php echo $article['_id']; ?>">
                        Edit
                    </a>
                    | <a href="#" onclick="confirmDelete('<?php echo $article['_id']; ?>')">
                        Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div id="navigation">
        <div class="prev">
            <?php if($currentPage !== 1): ?>
            <a href="<?php echo $_SERVER['PHP_SELF'].'?page='.($currentPage - 1); ?>">Previous</a>
            <?php endif; ?>
        </div>
        <div class="page-number">
            <?php echo $currentPage; ?>
        </div>
        <div class="next">
            <?php if($currentPage !== $totalPages): ?>
            <a href="<?php echo $_SERVER['PHP_SELF'].'?page='.($currentPage + 1); ?>">Next</a>
            <?php endif; ?>
        </div>
        <br class="clear" />
    </div>
    <div class="write" style="text-align: right; width: 600px;">
        <a href="blogpost.php">write</a>
        <br/>
        <br/>

    </div>
</div>
</body>
</html>

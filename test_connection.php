<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PHP MongoDB 테스트</title>
</head>
<body>
<?php
try{
    $mongo = new Mongo(); // mongoDB 연결
    $databases = $mongo->listDBs(); //데이터베이스 열거
    echo '<pre>';
    print_r($databases);
    $mongo->close();
}catch(MongoConnectionException $e){
    //연결 오류 처리
    die($e->getMessage());
}
?>
</body>
</html>

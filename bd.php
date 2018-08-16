<?php
$connectStr = "NEWCONTACTBASE_DEV";
$dsn = "oci:dbname=" . $connectStr . ";charset=AL32UTF8";
$user = 'U_DERIBOLOT';
$password = '12345';
$db=null;
try {
    $db = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    $answer = $e->errorInfo()[2];
    echo "Подключение к БД провалилось. ".$answer;
}

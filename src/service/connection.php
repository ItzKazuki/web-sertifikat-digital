<?php

include_once 'MyDatabase.php';
include_once 'DotEnv.php';

(new DotEnvEnvironment)->load(__DIR__ . '/../../');

$db = new MyConnection($_ENV['MYSQL_HOST'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $_ENV['MYSQL_DB']);
$conn = $db->getConnection();

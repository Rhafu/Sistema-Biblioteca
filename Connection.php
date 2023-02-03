<?php
session_start();
$base = "http://localhost/biblioteca";

$_dns     = "mysql:host=localhost;port=3306;dbname=biblioteca_sistema";
$_usuario = "root";
$_senha   = "";

try
{
    $pdo = new PDO($_dns,$_usuario,$_senha);
}
catch(PDOException $erro)
{
    echo "ERRO NA CONEXÃO: ".$erro->getCode()."<br>";
    echo "Messagem: <br>".$erro->getMessage();
}
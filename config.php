<?php

// On enregistre notre autoload.
function chargerClasse($classe) {
    require 'class/' .$classe. '.php';
}

spl_autoload_register('chargerClasse');

session_start();

$db=new PDO('mysql:host=localhost;dbname=at203_poo;port=8888','root', 'root');
// On émet une alerte à chaque fois qu'une requête a échoué.
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

?>
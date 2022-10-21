<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");


spl_autoload_register(function ($class){
    $path = "classes/".$class.".php";
    include $path;
});


// instantiate classes
$db = new Dbc();
$book = new Book();
$dvd = new Dvd();
$furniture = new Furniture();

// fetch products
$mainArray = array($dvd->getProducts(),$book->getProducts(),$furniture->getProducts());
echo json_encode($mainArray);

// Post products
$book->postProduct();
$dvd->postProduct();
$furniture->postProduct();

// delete products
$book->deleteProduct();
$dvd->deleteProduct();
$furniture->deleteProduct();

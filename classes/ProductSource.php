<?php

interface GetProduct
{
    public function getProducts();
}

abstract class ProductSource extends Dbc
{
    abstract protected function prepareProduct();
    abstract public function postProduct();
    abstract public function deleteProduct();
}
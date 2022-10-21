<?php

class Book extends ProductSource implements GetProduct
{
    private $sku;
    private $name;
    private $price;
    private $weight;
    // public $content;

    protected function prepareProduct()
    {
        $content = json_decode(file_get_contents("php://input", true), true);

        $this->sku = $content['sku'];
        $this->name = $content['name'];
        $this->price = $content['price'];
        $this->weight = $content['weight'];

        $insertIntoBook = "INSERT INTO book(name, sku, price, weight) 
            VALUES('$this->name','$this->sku','$this->price','$this->weight')";

        $sql1 = "SELECT * FROM dvd WHERE sku='$this->sku'";
        $sql2 = "SELECT * FROM book WHERE sku='$this->sku'";
        $sql3 = "SELECT * FROM furniture WHERE sku='$this->sku'";

        $uniqueSku1 = $this->connection()->query($sql1);
        $uniqueSku2 = $this->connection()->query($sql2);
        $uniqueSku3 = $this->connection()->query($sql3);

        if ($uniqueSku1->num_rows > 0 || $uniqueSku2->num_rows > 0 || $uniqueSku3->num_rows > 0) {
            $res = ['status' => 1, 'message' => "BOOK Sku must be unique!"];
            http_response_code(400);
        } else if ($this->connection()->query($insertIntoBook)) {
            $res = [
                'status' => http_response_code(200),
                'message' => "BOOK Prodect is inserted successful"
            ];
        } else {
            $res = ["status" => 0, 'message' => 'Failed to create BOOK record!'];
        }
        echo json_encode($res);
    }

    public function postProduct()
    {
        $content = json_decode(file_get_contents("php://input", true), true);
        $_SERVER['REQUEST_METHOD'] === 'POST' && array_key_exists('weight', $content) && $this->prepareProduct();
    }

    public function getProducts()
    {
        $sql = "SELECT * FROM book";

        $result = $this->connection()->query($sql);

        if (empty($result)) {
            $sql = "CREATE TABLE book (
                name VARCHAR(30) NOT NULL,
                sku VARCHAR(30) UNIQUE NOT NULL,
                price INT(10) UNSIGNED NOT NULL,
                weight INT(10) UNSIGNED NOT NULL
                )";
            if ($this->connection()->query($sql) == true) {
                echo "BOOK Table is created successfully";
            } else {
                echo "BOOK table error " . $this->connection()->error;
            }
        }
        $arrayedData = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $arrayedData;
    }
    public function deleteProduct()
    {
        if (isset($_GET['sku'])) {
            $sku = $_GET['sku'];
            $sql = "DELETE FROM book WHERE sku='$sku'";

            if ($this->connection()->query($sql)) {
                $res = ['status' => 1, 'message' => "DVD Record deleted successfully!"];
            } else {
                $res = ['status' => 0, 'message' => "Failed to delete DVD record!"];
            }
            echo json_encode($res);
        }
    }
}

<?php

class Dvd extends ProductSource implements GetProduct
{
    private $sku;
    private $name;
    private $price;
    private $size;

    protected function prepareProduct()
    {
        $content = json_decode(file_get_contents("php://input", true), true);
        $this->sku = $content['sku'];
        $this->name = $content['name'];
        $this->price = $content['price'];
        $this->size = $content['size'];

        $insertIntoDvd = "INSERT INTO dvd(name,sku,price,size) VALUES(
            '$this->name','$this->sku','$this->price','$this->size'
        )";
        $sql1 = "SELECT * FROM dvd WHERE sku='$this->sku'";
        $sql2 = "SELECT * FROM book WHERE sku='$this->sku'";
        $sql3 = "SELECT * FROM furniture WHERE sku='$this->sku'";

        $uniqueSku1 = $this->connection()->query($sql1);
        $uniqueSku2 = $this->connection()->query($sql2);
        $uniqueSku3 = $this->connection()->query($sql3);
        
        if ($uniqueSku1->num_rows > 0 || $uniqueSku2->num_rows > 0 || $uniqueSku3->num_rows > 0) {
            $res = ['status' => 1, 'message' => "DVD Sku must be unique!"];
            http_response_code(400);
        } else if ($this->connection()->query($insertIntoDvd)) {
            $res = [
                'status' => http_response_code(200),
                'message' => "DVD Prodect is inserted successful"
            ];
        } else {
            $res = ["status" => 0, 'message' => 'Failed to create DVD record!'];
        }
        echo json_encode($res);
    }

    public function postProduct()
    {
        $content = json_decode(file_get_contents("php://input", true), true);
        $_SERVER['REQUEST_METHOD'] === "POST" && array_key_exists("size", $content) && $this->prepareProduct();
    }

    public function getProducts()
    {
        $sql = "SELECT * FROM dvd";
        $result = $this->connection()->query($sql);

        if (empty($result)) {
            $sql = "CREATE TABLE dvd(
                name VARCHAR(30) NOT NULL,
                sku VARCHAR(30) UNIQUE NOT NULL,
                price INT(10) UNSIGNED NOT NULL,
                size INT(10) UNSIGNED NOT NULL
            )";
            if ($this->connection()->query($sql)) {
                echo "DVD Table is created successfully";
            } else {
                echo "DVD table error " . $this->connection()->error;
            }
        }
        $arrayedData = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $arrayedData;
    }
    public function deleteProduct()
    {
        if (isset($_GET['sku'])) {
            $sku = $_GET['sku'];
            $sql = "DELETE FROM dvd WHERE sku='$sku'";

            if ($this->connection()->query($sql) === true) {
                $res = ['status' => 1, 'message' => "DVD Record deleted successfully!"];
            } else {
                $res = ['status' => 0, 'message' => "Failed to delete DVD record!"];
            }
            echo json_encode($res);
        }
    }
}

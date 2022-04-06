<?php
require_once('DataHandler.php');
class Products
{

    function __construct()
    {
        $this->datahandler = new dataHandler( "localhost", "mysql", "starDUNK", "root", "", );
        //$this->datahandler = new Output();
    }
    function __destruct()
    {

    }
    function createProduct()
    {
        echo"oi mate wa doe je swa";
        $product_code = $_POST["product_type_code"];
        $supplier_id = $_POST["supplier_id"];
        $productname = $_POST["product_name"];
        $product_price = $_POST["product_price"];
        $other_pr =  $_POST["other_product_details"];

        if (empty($product_code) or empty($supplier_id) or empty($productname) or empty($product_price) or empty($other_pr)) {
            $msg = "Alle velden zijn vereist";
        } else {
            $sql = "INSERT INTO products (product_type_code, supplier_id, product_name, product_price, other_product_details) 
        VALUES('$product_code', '$supplier_id', '$productname', '$product_price', '$other_pr')";
            $lastid = $this->datahandler->createData($sql);

             $sql = "SELECT  
             product_id,
             product_type_code,
             supplier_id,
             product_name,
             CONCAT('€', REPLACE(product_price, '.', ',')) AS product_price,
             other_product_details  
             FROM products 
             WHERE product_id=$lastid";
            
            $result = $this->datahandler->readsData($sql);
            $res = $result->fetchAll();
            //laat weten of het gelukt is en laat het  resultaat zien
            return $res;


        }
    }
    function readProduct($id)
    {
        $sql = "SELECT product_name, CONCAT('€ ',REPLACE(product_price, '.', ','))product_price, other_product_details FROM products WHERE product_id=$id";
     $result = $this->datahandler->readsData($sql);
    $res = $result->fetchAll();
    return $res;  
    }
    function listProduct($p = 1)
    {
        $item_per_page = 5;
        $position = (($p - 1) * $item_per_page);
        $sql = "SELECT product_id,
        product_type_code,
        supplier_id,
        product_name,
        CONCAT('€', REPLACE(product_price, '.', ',')) AS product_price,
        other_product_details 
        FROM products LIMIT $position, $item_per_page";
        $results = $this->datahandler->readsData($sql);
        $pages = $this->datahandler->countPages('SELECT COUNT(*) FROM products');
        

        // multiple returns use array > destructing prefererred though
        return array($results, $pages);
    }
    
    function updateProduct($colum, $value, $id)
    {
        $sql = "UPDATE products SET $colum='$value' WHERE product_id=$id";
        $result = $this->datahandler->updateData($sql);
        return	$result;
    }
    function collectUpdateproduct($id)
    {
        if (isset($_REQUEST['submit'])){
            echo"formulier ontvangen";
            $id = $_REQUEST["product_id"];
            $product_code = $_REQUEST["product_type_code"];
            $supplier_id = $_REQUEST["supplier_id"];
            $product_name = $_REQUEST["product_name"];
            $product_price = $_REQUEST["product_price"];
            $other_pr =  $_REQUEST["other_product_details"];

            $sql = "UPDATE Products SET product_type_code = '$product_code',
            supplier_id = '$supplier_id', 
            product_name = '$product_name', 
            product_price = '$product_price', 
            other_product_details = '$other_pr' 
             WHERE product_id = $id";
             $rowcount = $this->datahandler->updateData($sql);
             echo "$rowcount product(s) updated";


        }else{
            $result = $this->readProduct($id);

            var_dump($result[0]['product_id']);
            $product_id = $result[0]['product_id'];
            $product_code = $result[0]['product_type_code'];
            $supplier_id = $result[0]['supplier_id'];
            $product_name = $result[0]['product_name'];
            $product_price = $result[0]['product_price'];
            $other_pr = $result[0]['other_product_details'];

            $html = "";
            $html .= "<form action=\"index.php?op=update&id=$product_id\" method=\"POST\">";

            $html .= "<label for=\"product_id\">product_id:</label><br>";
            $html .= " <input type=\"text\" name=\"product_id\" value=\"$product_id\" readonly></input><br>";

            $html .= "<label for=\"product_type_code\">Product_type_code:</label><br>";
            $html .= " <input type=\"text\" name=\"product_type_code\" value=\"$product_code\"></input><br>";

            $html .= "<label for=\"supplier_id\">supplier_id:</label><br>";
            $html .= "<input type=\"text\" name=\"supplier_id\" value=\"$supplier_id\"></input><br>";

            $html .= "<label for=\"product_name\">product_name:</label><br>";
            $html .= "<input type=\"text\" name=\"product_name\" value=\"$product_name\"></input><br>";

            $html .= "<label for=\"product_price\">product_price:</label><br>";
            $html .= "<input type=\"text\" name=\"product_price\" value=\"$product_price\"></input><br>";

            $html .= "<label for=\"other_product_details\">other product details:</label><br>";
            $html .= "<input type=\"text\" name=\"other_product_details\" value=\"$other_pr\"></input><br><br>";

            $html .= "<input type=\"submit\" name=\"submit\" value=\"update\" placeholder=\"Update Old Product\"></input><br>";
            $html .= "</form>";
            echo $html;
        }
    }
    function collectDeleteproduct($id)
    {
       $msg = $this->deleteproduct($id);
        include "delete.php";
           
    }
    function deleteproduct($id)
    {
       $sql = "DELETE  FROM products WHERE product_id=$id";
        $result = $this->datahandler->deleteData($sql);
        return $result;
           
    }
    function searchProduct($term)
    {
//        var_dump($term);
        $sql = "SELECT * FROM products WHERE product_name LIKE '%$term%'";
        
        $result = $this->datahandler->readsData($sql);
        $res = $result->fetchAll();
        return $res;
    }
}

?>
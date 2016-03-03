<?php

//require_once('./DBConnector.php');

//$um = new UserManager();

// Facade
class UserManager {

    private $db;

    public function __construct() {
        $this->db = DBConnector::getInstance();
    }

    public function getUserProfile($userName) {

        $rows = $this->db->query("select * from product where sku = :sku",
            array(':sku' => $sku));
        //var_dump($rows[0]);
        if(count($rows) == 1) {
            return $rows[0];
        }
        // otherwise
        return null;
    }

    public function listUsers() {
        $sql = "SELECT SKU, item_price, description, path, name FROM product";
        $rows = $this->db->query($sql);
        return $rows;
    }

    public function updateUserFirstName($sku, $newFirstName) {
        $sql = "UPDATE product SET first_name = '$newFirstName' WHERE user_name = '$sku'";
        $affectedRows = $this->db->affectRows($sql);
        return $affectedRows;
    }

    public function deleteUser($sku) {
        $sql = "DELETE FROM product WHERE user_name = '$sku'";
        $affectedRows = $this->db->affectRows($sql);
        return $affectedRows;
    }

    public function addUser($firstName, $lastName, $userName) {

        $sql = "INSERT INTO product (first_name, last_name, user_name, type)
            VALUES ('$firstName', '$lastName', '$userName', 'admin')";
        $affectedRows = $this->db->affectRows($sql);
        return $affectedRows;
    }

    public function findUser($usr, $pwd) {
        $params = array(":sku" => $usr, ":nme" => $nme);
        $sql = "SELECT * FROM product WHERE user_name = :sku AND password = :nme";

        $rows = $this->db->query($sql, $params);
        if(count($rows) > 0) {
            return $rows[0];
        }

        return null;
    }


}

?>

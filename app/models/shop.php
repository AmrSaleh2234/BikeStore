<?php


namespace MVCPHP\models;


use MVCPHP\libraries\Database;

/**
 * Class shop
 * @package MVCPHP\models
 */
class shop
{
    private $db;
    public function __construct() {
        global $registry;
        $this->db = $registry->get('db');
    }

    /**
     * @param $id
     * @param $username
     * @return bool
     */
    public function insertShoppingCart($id,$username)
    {
        $row= $this->db->select('shoppingcart','*',"username ='$username' AND productId ='$id'");

        if($this->db->rowCount($row)>0)
        {
            return false;
        }
        else
        {
            return ($this->db->insert('shoppingcart',[$username,$id])) ?true :false;
        }


    }

    /**
     * @param $username
     * @return object
     */
    public function getAllData($username)
    {
        return $this->db->select('shoppingcart INNER JOIN products ON shoppingcart.productId = products.productId','shoppingcart.* , products.*',"shoppingcart.username = '$username'");
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getPaymentsForUsername($username) {
        $this->db->query('SELECT * FROM payment WHERE username = :user');
        $this->db->bind(':user', $username);
        return $this->db->resultSet();
    }

    /**
     * @param $username
     * @param $id
     * @return bool
     */
    public function search($username, $id) {
        $this->db->query('SELECT * FROM shoppingcart WHERE username = :user AND productId = :id');
        $this->db->bind(':user', $username);
        $this->db->bind(':id', $id);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @return boolean
     */
    public function makeOrder($data) {
        $this->db->query('INSERT INTO orders(productId, username, orderQuantity) VALUES(:id, :user, :quantity)');
        $this->db->bind(':id', $data['productId']);
        $this->db->bind(':user', $data['username']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->execute();
        $this->db->query('UPDATE products SET quantity = :quantity WHERE productId = :id');
        $this->db->bind(':id', $data['productId']);
        $this->db->bind(':quantity', $data['newQuantity']);
        $this->db->execute();
        $this->db->query('UPDATE payment SET money = :money WHERE username = :user AND visaNumber = :visa');
        $this->db->bind(':money', $data['visaMoney']);
        $this->db->bind(':user', $data['username']);
        $this->db->bind(':visa', $data['visaNumber']);
        return $this->db->execute();
    }
}
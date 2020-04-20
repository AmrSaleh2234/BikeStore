<?php


namespace MVCPHP\models;
/**
 * Class product
 * @package MVCPHP\models
 */
class product
{
    private $db;

    /**
     * product constructor.
     */
    public function __construct()
    {
        global $registry;
        $this->db = $registry->get('db');
    }

    /**
     * @return boolean
     */
    public function getAllProducts()
    {
        return $this->db->select('products');
    }

    /**
     * @param $id
     * @return boolean
     */
    public function getProductById($id)
    {
        return $this->db->select('products', '*', "productId= '$id'");
    }

    /**
     * @param $data
     * @return boolean
     */
    public function addProduct($data)
    {
        return $this->db->insert('products', [$data['username'], $data['name'], $data['photo'], $data['feature'], $data['price'], $data['quantity'], $data['forRent'], $data['isBike'], $data['isNew']], 'username, name, photoName, features, price, quantity, rentStatus, isBike, isNew');
    }

    /**
     * @param $username
     * @return object
     */
    public function allProductsByUsername($username)
    {

        return $this->db->select('products', '*', "username ='$username'");

    }

    /**
     * @param $data
     * @return bool
     */
    public function editProduct($data)
    {
        if (!empty($data['photo'])) {
            return $this->db->update('products', ['name' => $data['name'], 'photoName' => $data['photo'], 'features' => $data['features'], 'quantity' => $data['quantity'], 'rentStatus' => $data['rentStatus'], 'isBike' => $data['isBike'], 'isNew' => $data['isNew']], 'productId = ' . $data['id'] . '');
        } else {
            return $this->db->update('products', ['name' => $data['name'], 'features' => $data['features'], 'quantity' => $data['quantity'], 'rentStatus' => $data['rentStatus'], 'isBike' => $data['isBike'], 'isNew' => $data['isNew']], 'id = ' . $data['id'] . '');
        }
    }

    /**
     * @param $id
     * @return boolean
     */
    public function deleteProduct($id)
    {
        return $this->db->delete('products', "productId ='$id'");
    }

    /**
     * @param $data
     * @return boolean
     */
    public function renting($data)
    {
        return $this->db->insert('products',['username'=>$data['username'],'productId'=>$data['id']],'username , productId');
    }


}
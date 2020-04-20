<?php


namespace MVCPHP\models;

use MVCPHP\libraries\Database;

/**
 * Class payment
 * @package MVCPHP\models
 */
class payment
{
    private $db;
    public function __construct() {
        global $registry;
        $this->db = $registry->get('db');
    }

    /**
     * @param $data
     * @return boolean
     */
    public function addPayment($data)
    {
        return $this->db->insert('payment',[$data['username'],$data['visaNum'],$data['pin'],$data['money']]);
    }

    /**
     * @param $username
     * @return object
     */
    public function allPayment($username)
    {
        return $this->db->select('payment','*',"username ='$username'");
    }

    /**
     * @param $VisaNum
     * @return object
     */
    public function getPymentByNum($VisaNum)
    {
        return $this->db->select('payment','*',"visaNumber = $VisaNum");
    }
}
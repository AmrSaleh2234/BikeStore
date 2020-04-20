<?php


namespace MVCPHP\models;

/**
 * Class Users
 * @package MVCPHP\models
 * @uses \MVCPHP\libraries\Database
 */
class Users
{


    private $db;

    public function __construct()
    {
        global $registry;
        $this->db = $registry->get('db');
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function login($username, $password)
    {
        $row = $this->db->select('users', '*', "username ='$username'");
        if (password_verify($password, $row[0]->password)) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * find user  by email
     * @param $email
     * @return object
     */
    public function gatUserByEmail($email)
    {
        return $this->db->select('users', '*', "email = '$email'");

    }

    public function getUserByUsername($username)
    {
        return $this->db->select('users', '*', "username = '$username' ");

    }


    /**
     * register function
     * @param $data
     * @return boolean
     */
    public function register($data)
    {

        return $this->db->insert('users',
            [$data['username'], $data['firstName'], $data['lastName'], $data['email'], $data['pass'], $data['gender'], $data['phone'], '0'],
            'username , firstName ,lastName ,email ,password ,gender , telephone ,adminStatus ');


    }

    /**
     * @param $data
     * @return bool
     */
    public function update($data)
    {
        if (empty($data['photo'])) {
            if (empty($data['password'])) {
                $this->db->query('UPDATE users SET firstName = :fname, lastName = :lname, email = :email, telephone = :phone WHERE username = :username');
                $this->db->bind(':fname', $data['firstName']);
                $this->db->bind(':lname', $data['lastName']);
                $this->db->bind(':email', $data['email']);
                $this->db->bind(':phone', $data['phone']);
                $this->db->bind(':username', $data['username']);
            } else {
                $this->db->query('UPDATE users SET firstName = :fname, lastName = :lname, email = :email, password = :pass, telephone = :phone WHERE username = :username');
                $this->db->bind(':fname', $data['firstName']);
                $this->db->bind(':lname', $data['lastName']);
                $this->db->bind(':email', $data['email']);
                $this->db->bind(':pass', $data['password']);
                $this->db->bind(':phone', $data['phone']);
                $this->db->bind(':username', $data['username']);
            }
        } else {
            if (empty($data['password'])) {
                $this->db->query('UPDATE users SET firstName = :fname, lastName = :lname, email = :email, telephone = :phone, avatarName = :avatar WHERE username = :username');
                $this->db->bind(':fname', $data['firstName']);
                $this->db->bind(':lname', $data['lastName']);
                $this->db->bind(':email', $data['email']);
                $this->db->bind(':phone', $data['phone']);
                $this->db->bind(':username', $data['username']);
                $this->db->bind(':avatar', $data['photo']);
            } else {
                $this->db->query('UPDATE users SET firstName = :fname, lastName = :lname, email = :email, password = :pass, telephone = :phone, avatarName = :avatar WHERE username = :username');
                $this->db->bind(':fname', $data['firstName']);
                $this->db->bind(':lname', $data['lastName']);
                $this->db->bind(':email', $data['email']);
                $this->db->bind(':pass', $data['password']);
                $this->db->bind(':phone', $data['phone']);
                $this->db->bind(':username', $data['username']);
                $this->db->bind(':avatar', $data['photo']);
            }
        }
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $username
     * @return object
     */
    public function getByUsername($username)
    {
        return $this->db->select('users', '*', "username = '$username'");
    }

    /**
     * @return object
     */
    public function allServices()
    {

        return $this->db->select('bikeservicing', '*', null, 'serviceDate DESC');

    }

    /**
     * @return object
     */
    public function allUsers()
    {

        $this->db->select('users', '*', null, 'createdAt DESC');
        return $this->db->resultSet();
    }

    public function allServiceForUsername($username)
    {

        return $this->db->select('bikeservicing', '*', "username= '$username'", 'serviceDate DESC');

    }

    /**
     * @param $date
     * @return bool
     */
    public function checkServiceDate($date)
    {
        $this->db->query('SELECT * FROM bikeservicing WHERE serviceDate = :date');
        $this->db->bind(':date', $date);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $date
     * @return bool
     */
    public function addService($date)
    {

        return $this->db->insert('bikeservicing', [getUsername(), $date], 'username, serviceDate');
    }

    /**
     * @param $username
     * @return mixed<p>
     * all rents
     * </p>
     */
    public function getRents($username)
    {
        $this->db->query('SELECT rentbike.rentDate, products.* FROM rentbike INNER JOIN products ON rentbike.productId = products.productId WHERE rentbike.username = :username');
        $this->db->bind(':username', $username);
        return $this->db->resultSet();
    }

    /**
     * @param $username
     * @return object<p>
     * if not found return false otherwise return object
     * </p>
     */

    public function usernameExist($username)
    {
        return $this->db->select('users','*',"username = '$username'");

    }

    /**
     * @param $username
     * @return  object
     */
    public function getOrdersForUser($username)
    {
        return $this->db->select('orders INNER JOIN products ON orders.productId = products.productId','orders.orderQuantity, products.*',"orders.username = '$username'");

    }


}
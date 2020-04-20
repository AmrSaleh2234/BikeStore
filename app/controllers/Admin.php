<?php


namespace MVCPHP\controllers;


use MVCPHP\libraries\Controller;

/**
 * Class Admin
 * @package MVCPHP\controllers
 * @uses \MVCPHP\libraries\Controller
 */

class Admin extends Controller
{
    private $userModel;
    public function __construct() {
        $this->userModel = $this->model('users');
    }

    /**
     * get all services
     */
    public function services() {
        $data = [
            'services' => $this->userModel->allServices()
        ];
        $this->view('admin/services', $data);
    }

    /**
     * get all users
     */
    public function users() {
        $data = [
            'usres' => $this->userModel->allUsers()
        ];
        $this->view('admin/users', $data);
    }

}
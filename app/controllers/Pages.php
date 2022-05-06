<?php

namespace MVCPHP\controllers;//this name space for autoloader can delete MVCPHP and can get dir for class after backslash

use MVCPHP\libraries\Controller;//this to define class controller in name space MVCPHP\libraries\

/**
 * Class Pages
 * @package MVCPHP\controllers
 * @uses \MVCPHP\libraries\Controller
 */
class Pages extends Controller
{
    private $userModel;
    public function __construct() {
        $this->userModel = $this->model('product');
    }

    /**
     * <b>index</b>view all product in view /pages/index
     */
    public function index() {
      //echo "hello world";
        $data = [
            'products' => $this->userModel->getAllProducts()
        ];
        $this->view('pages'.DS.'index', $data);
    }
}
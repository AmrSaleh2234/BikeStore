<?php


namespace MVCPHP\libraries;

/**
 * <b>Class Registry</b>
 * store data of classes
 * @package MVCPHP\libraries
 */
class Registry {
    private $data =array();
    public  function  set($key ,$value) {
        $this->data[$key]=$value;
    }
    public function  get($key) {
        return (isset($this->data[$key]) ? $this->data[$key] : false);
    }
}


<?php

namespace MVCPHP\libraries;

class CreateView
{
  public static function create($view,$data=[])
  {
    if(file_exists('..'.DS.'app'.DS.'views'.DS.$view.'.php' ))
    {
      require_once '..'.DS.'app'.DS.'views'.DS.$view.'.php';
    }
    else
    {
      //view does not exist
      die('view is not exists');

    }
 }
}
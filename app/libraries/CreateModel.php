<?php

namespace MVCPHP\libraries;

class  CreateModel
{
  

  public static  function create($model)
  {
    $modelName='MVCPHP\models\\'.$model;//this is class name model with namespace
    //Instatiate model
    return new $modelName();
  }
}
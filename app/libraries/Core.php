<?php
  /*
   * App core class
   * create URL and load core controller
   * url format controller /method / params
   */
   namespace MVCPHP\libraries;
   /**
    * Class Core
    * @package MVCPHP\libraries
    */
  class Core
  {
      protected $currentController='pages';
      protected $currentMethod ='index';
      protected $params=[];

      /**
       * Core constructor.
       * call class of controller by url
       */
      public function __construct()
      {
         //print_r($this->getUrl()) ;
          $url =$this->getUrl();
          //LOOK IN Controller for first value



          if(file_exists('..'.DS.'app'.DS.'controllers'.DS.ucwords($url[0]).'.php'))
          {
            //if exists set as controller
              $this->currentController=ucwords($url[0]);
              //unset zero index
              unset($url[0]);
          }
          $classControllers='MVCPHP\controllers\\'.$this->currentController;
          //Require controller
          //require_once '../app/controllers/'.$this->currentController.'.php';
          $classController =new $classControllers();
          //CHECK for second part of url
          //var_dump($url);
          if(isset($url[1]))
          {
              if(method_exists($classController,$url[1]))
              {
                  $this->currentMethod=$url[1];
                  unset($url[1]);
              }
          }
         // GET PARAMS

          $this->params =$url?array_values($url) : [];
          //var_dump($classController);
           call_user_func_array([$classController,$this->currentMethod],$this->params);

      }

      /**
       * this fun can get url after localhost/public/
       * @return array
       */

      private function getUrl()
      {
          //echo $_GET['url'];
          if(isset($_GET['url']))
          {
            $url=rtrim($_GET['url'],'/');
            $url=filter_var($url,FILTER_SANITIZE_URL);
            $url=explode('/',$url);
            return $url;
          }
      }
  }
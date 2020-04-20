<?php

    namespace MVCPHP\libraries;
    /**
     * <b>Class Controller</b>Base controller
     * loads the model end views
     * @package MVCPHP\libraries
     */
    class Controller
    {

        //load model
        /**
         * <b>model</b>add name space to class name
         * @param $model<p>
         * this var is model class name
         *</p>
         * @return object
         */
        public function model($model)
        {
            //Required model file
            echo '<br'.$model.'<br>';
           $modelName='MVCPHP\models\\'.$model;//this is class name model with namespace
            //Instatiate model
            return new $modelName();
        }

        /**
         * <b>view</b> require view file
         * @param $view<p>
         * this view is html , css  ,js files in view dir name
         * </p>
         * @param array $data<p>
         * data we want to send it to view
         * </p>
         */
        public function view($view,$data=[])
        {
            //check view file
            echo '..'.DS.'app'.DS.'views'.DS.$view.'.php' ;
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
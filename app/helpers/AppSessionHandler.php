<?php
namespace MVCPHP\helpers;
define('SESSION_SAVE_PATH',dirname(realpath(__FILE__)).DIRECTORY_SEPARATOR.'session');

/**
 * <b>Class AppSessionHandler</b>
 * class can encrypt data in session and return it decrypted
 * @author Amr Saleh
 * @package MVCPHP\helpers
 * @uses \SessionHandler
 */

class AppSessionHandler extends \SessionHandler
{
    private $sessionName='MYAPPSESS';
    private $sessionMaxLifetime=0;
    private $sessionSSL = false ;
    private $sessionHTTPOnly= true;
    private $sessionPath='/';
    private $sessionDomain ='localhost';
    private $sessionSavePath =SESSION_SAVE_PATH;
    private $ttl= 30;
 // public  $sessionCipherKey='ollehdlrow';
//    private $sessionCipherAlgo=MCRYPT_BLOWFISH;
//    private $sessionCipherMode= MCRYPT_MODE_ECB;

     public function  __construct()
     {

         ini_set('session.use_cookies',1);
         ini_set('session.use_only_cookies',1);
         ini_set('session.use_trans_sid',0);
         ini_set('session.session_set_save_handler','files');
        session_name($this->sessionName);
        session_save_path($this->sessionSavePath);
        session_set_cookie_params($this->sessionMaxLifetime,$this->sessionPath,
            $this->sessionDomain,$this->sessionSSL,$this->sessionHTTPOnly);

        session_set_save_handler($this,true);
     }
     public function read($session_id)
     {
        return parent::read($session_id); // TODO: Change the autogenerated stub
     }
     public function write($session_id, $session_data)
     {
        return parent::write($session_id, $session_data); // TODO: Change the autogenerated stub
     }
     public function __get($key)
     {
         return   false !== $_SESSION[$key]  ? $_SESSION[$key] : false;
     }
     public function __set($key, $value)
     {
         $_SESSION[$key]=$value;
     }
     public function __isset($key)
        {
           return isset($_SESSION[$key]) ? true :false ;
        }
     public function start()
     {
           if(''===session_id())
           {
               if(session_start())
               {
                    $this->setSessionStartTime();
                    $this->checkSessionValdity();
               }
           }
     }
     private function setSessionStartTime()
     {
         if(!isset($this->sessionStartTime))
         {
             $this->sessionStartTime=time();
         }
         return true;
     }
     private function checkSessionValdity()
     {
         if((time()- $this->sessionStartTime) > ($this->ttl*60))
         {
             $this->renewSession();
             $this->setFingerPrint();
         }
         return true;
     }
     private function renewSession()
     {
         $this->sessionStartTime =time();
        session_regenerate_id(true);

     }
     public function kill()
     {
         session_unset();
         setcookie($this->sessionName,'',time()-1000,$this->sessionPath,
         $this->sessionDomain,$this->sessionSSL,$this->sessionHTTPOnly);
         session_destroy();
     }
     private function setFingerPrint()
     {
         $userAgentId =$_SERVER['HTTP_USER_AGENT'];
         $this->CipherKey= random_bytes(16);
         $sessionID=session_id();
         $this->fingerPrint =md5($userAgentId.$this->CipherKey.$sessionID);
     }

     public function checkFingerPrint()
     {
         if(!isset($this->fingerPrint))
         {
             $this->setFingerPrint();
         }

         $fingerPrint=md5($_SERVER['HTTP_USER_AGENT'].$this->CipherKey.session_id());
         if($fingerPrint === $this->fingerPrint)
         {
             return true;
         }
         return false;
     }

}




<?php



 //flash message helper
//Example flash('register_success' ,'You are registered');
//Display In VIEW - <?php echo flash('register_success');

function flash($name='',$message ='' ,$class='alert alert-success')
{
    if(!empty($name))
    {
        if(!empty($message) && empty($_SESSION[$name]))
        {
            if(!empty($_SESSION[$name]))
            {
                unset($_SESSION[$name]);
            }
            if(!empty($_SESSION[$name.'_class']))
            {
                unset($_SESSION[$name.'_class']);
            }
            $_SESSION[$name]=$message;
            $_SESSION[$name.'_class']=$class;
        }
        elseif (empty($message) && !empty($_SESSION[$name]))
        {
            $class= !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : '';
            echo '<div class=" '.$class.'" id ="msg_flash">'.$_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
        }

    }
}
function isloggedIn()
{
    if(isset($_SESSION['username']))
    {
        return true;
    }
    else
    {
        return false;
    }
}
function isAdmin() {
    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == true) {
        return true;
    } else {
        return false;
    }
}
function getUsername() {
    return isset($_SESSION['username'])? $_SESSION['username'] : false;
}

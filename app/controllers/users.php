<?php


namespace MVCPHP\controllers;
//this name space for autoloader can delete MVCPHP and can get dir for class after backslash

use MVCPHP\libraries\controller;//this to define class controller in name space( MVCPHP\libraries\)

/**
 * Class users
 * @package MVCPHP\controllers
 * @uses \MVCPHP\libraries\Controller
 */
class users extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('Users');
    }

    /*
     * user Register
     * @params none
     * @return none
     * @author Team
     */
    public function register()//********************************** start register *******************************************************************
    {
        //if user login already and this function in helpers folder
        if (isLoggedIn()) {
            redirect('pages');
        } else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'firstName' => trim($_POST['firstName']),
                    'lastName' => trim($_POST['lastName']),
                    'username' => trim($_POST['username']),
                    'email' => trim($_POST['email']),
                    'pass' => trim($_POST['pass']),
                    'rePass' => trim($_POST['rePass']),
                    'phone' => trim($_POST['phone']),
                    'gender' => '1',
                    'firstName_err' => '',
                    'lastName_err' => '',
                    'username_err' => '',
                    'email_err' => '',
                    'pass_err' => '',
                    'rePass_err' => '',
                    'phone_err' => ''
                ];
                //validate firstName
                if (empty($data['firstName'])) {
                    $data['firstName_err'] = 'this is require*';
                }

                //validate lastName
                if (empty($data['lastName'])) {
                    $data['lastName_err'] = 'this is require*';
                }
                //validate email
                if (empty($data['email'])) {
                    $data['email_err'] = 'this is require*';
                } elseif ($this->userModel->gatUserByEmail($data['email'])) {


                    $data['email_err'] = 'this email is already exisits*';

                }

                //validate username
                if (empty($data['username'])) {
                    $data['username_err'] = 'this is require*';
                } else {
                    if ($this->userModel->getUserByUsername($data['username']))//this is method return row of user name if found
                    {
                        $data['username_err'] = 'this username ixs already exists*';
                    }


                }

                //validate password
                if (empty($data['pass'])) {
                    $data['pass_err'] = 'this is require*';
                } elseif (strlen($data['pass']) < 8) {
                    $data['pass_err'] = 'password should be greater than 7';
                }
                //validate confirm password
                if (empty($data['rePass'])) {
                    $data['rePass_err'] = 'this is require*';
                }
                if ($data['pass'] != $data['rePass']) {
                    $data['rePass_err'] = "password not match";
                }
                //validate phone
                if (empty($data['phone'])) {
                    $data['phone_err'] = 'this is require*';
                }
                // if no error
                if (empty($data['firstName_err']) &&
                    empty($data['lastName_err']) &&
                    empty($data['email_err']) &&
                    empty($data['phone_err']) &&
                    empty($data['pass_err']) &&
                    empty($data['rePass_err']) &&
                    empty($data['username_err'])
                ) {
                    $data['pass'] = password_hash($data['pass'], PASSWORD_DEFAULT);
                    if ($this->userModel->register($data)) {
                        flash('register_success', 'You are registered and can log in ');
                        redirect('users/login');

                    } else {
                        flash('error', 'something went wrong', 'alert alert-danger');
                        redirect('pages/login');
                    }
                } // if have error view register with error
                else {
                    $this->view('users/register', $data);

                }

            } else {
                $data = [
                    'firstName' => '',
                    'lastName' => '',
                    'username' => '',
                    'email' => '',
                    'pass' => '',
                    'rePass' => '',
                    'phone' => '',
                    'gender' => '1',
                    'firstName_err' => '',
                    'lastName_err' => '',
                    'username_err' => '',
                    'email_err' => '',
                    'pass_err' => '',
                    'rePass_err' => '',
                    'phone_err' => ''

                ];
                $this->view('users/register', $data);

            }
        }
    }//******************************************************** end register *******************************************************************

    public function login()//*********************************  start login *******************************************************************
    {
        if (isLoggedIn()) {
            redirect('pages');
        } else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                    'username_err' => '',
                    'password_err' => ''
                ];
                if (empty($data['username'])) {
                    $data['username_err'] = 'this is require *';
                } elseif (!$this->userModel->getByUsername($data['username'])) {
                    $data['username_err'] = 'this username is not found register first';
                }
                if (empty($data['password'])) {
                    $data['password_err'] = 'this is require *';
                }
                if (empty($data['username_err']) && empty($data['password_err'])) {
                    $loggeedIn = $this->userModel->login($data['username'], $data['password']);
                    if ($loggeedIn) {
                        $this->createUserSession($loggeedIn);
                        redirect('pages');
                    } else {
                        $data['password_err'] = 'this is password is wrong ';
                        $this->view('users/login', $data);
                    }
                } else {
                    $this->view('users/view', $data);
                }


            } else {
                $data = [
                    'username' => '',
                    'password' => '',
                    'username_err' => '',
                    'password_err' => '',
                ];
                $this->view('users/login', $data);
            }
        }
    }
//******************************************************** end login *******************************************************************

    /**
     * this func create session when login
     */
    public function createUserSession($user)//*********************************  create session *******************************************************************
    {
        $_SESSION['username'] = $user[0]->username;
        $_SESSION['user_email'] = $user[0]->email;
        $_SESSION['user_name'] = $user[0]->fisrtName;
        $_SESSION['isAdmin'] = $user[0]->adminStatus;
        redirect('pages');
    }

    /**
     * this func unset session when user logout
     */
    public function logout()
    {
        unset($_SESSION['username']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['isAdmin']);

        session_destroy();
        redirect('users/login');

    }

    public function service()//*********************************  start service *******************************************************************
    {
        if (isLoggedIn()) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'serviceDate' => $_POST['serviceDateTime'],
                    'serviceDate_err' => '',
                    'services' => $this->userModel->allServiceForUsername(getUsername())
                ];
                $dateTime = $_POST['serviceDateTime'];
                $date = substr($dateTime, 0, 10);
                $time = substr($dateTime, 11, 5);
                $dateTime = $date . ' ' . $time . ':00';
                if ($this->userModel->checkServiceDate($dateTime)) {
                    $this->userModel->addService($dateTime);
                    flash('service-added', 'Service Added Successfully :)');
                    redirect('users/service');
                } else {
                    flash('service-added', 'This time isn\'t available try another time :(', 'alert alert-danger');
                    $this->view('users/service', $data);
                }
            } else {
                $data = [
                    'serviceDate' => date("Y-m-d") . 'T' . date("H:i"),
                    'serviceDate_err' => '',
                    'services' => $this->userModel->allServiceForUsername(getUsername())
                ];
                $this->view('users/service', $data);
            }
        } else {
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages/index');
        }
    }//******************************************************** end service *******************************************************************

    /**
     * show all rents
     */

    public function allRents()////*********************************  start all rents*******************************************************************
    {
        if (isLoggedIn()) {
            $data = [
                'rents' => $this->userModel->getRents(getUsername())
            ];
            $this->view('users/rent', $data);
        } else {
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages/index');
        }
    }

    //******************************************************** end all rents *******************************************************************

    public function edit()//*********************************  start edit user *******************************************************************
    {
        if (isLoggedIn()) {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'firstName' => trim($_POST['userFirstName']),
                    'lastName' => trim($_POST['userLastName']),
                    'username' => getUsername(),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['firstPassword']),
                    'phone' => trim($_POST['phone']),
                    'photo' => '',
                    'gender' => trim($_POST['gender']),
                    'firstName_err' => '',
                    'lastName_err' => '',
                    'username_err' => '',
                    'email_err' => '',
                    'phone_err' => '',
                ];
                $photoName = $_FILES['photo']['name'];
                $photoSize = $_FILES['photo']['name'];
                $photoTmp = $_FILES['photo']['tmp_name'];
                $photoType = $_FILES['photo']['type'];

                $photoAllowedExtention = array('jpeg', 'jpg', 'png', 'gif');
                $photoExtention = explode('.', $photoName);
                $photoExtention = end($photoExtention);
                $photoExtention = strtolower($photoExtention);
                if (empty($data['firstName'])) {
                    $data['firstName_err'] = 'Please fill the first name field';
                }
                if (empty($data['lastName'])) {
                    $data['lastName_err'] = 'Please fill the last name field';
                }
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please fill the email field';
                }
                if (empty($data['phone'])) {
                    $data['phone_err'] = 'Please fill the phone number field';
                }
                if (!in_array($photoExtention, $photoAllowedExtention) && !empty($photoName)) {
                    $data['photo_err'] = 'Sorry, The Extention Not Allowed :(';
                }

                if (empty($data['firstName_err']) &&
                    empty($data['lastName_err']) &&
                    empty($data['email_err']) &&
                    empty($data['phone_err']) &&
                    empty($data['photo_err'])) {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    if (!empty($photoName)) {
                        $randomNum = rand(0, 100000);
                        move_uploaded_file($photoTmp, 'img/uploads/' . $randomNum . '_' . $photoName);
                        $data['photo'] = $randomNum . '_' . $photoName;
                    }
                    if ($this->userModel->update($data)) {
                        flash('sucess-edit', 'Changes Saved Successfully');
                        redirect('users/edit');
                    } else {
                        flash('error', 'something went wrong', 'alert alert-danger');
                        redirect('pages/index');
                    }
                } else {
                    $this->view('users/edit', $data);
                }


            } else {
                $row = $this->userModel->usernameExist(getUsername());
                $data = [
                    'firstName' => $row[0]->firstName,
                    'lastName' => $row[0]->lastName,
                    'username' => $row[0]->username,
                    'email' => $row[0]->email,
                    'pass' => '',
                    'phone' => $row[0]->telephone,
                    'gender' => $row[0]->gender,
                    'firstName_err' => '',
                    'lastName_err' => '',
                    'username_err' => '',
                    'email_err' => '',
                    'pass_err' => '',
                    'repass_err' => '',
                    'phone_err' => '',
                ];
                $this->view('users/edit', $data);
            }

        } else {
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages/index');
        }

    }    //******************************************************** end edit *******************************************************************


    //******************************************************** start all order *******************************************************************
    public function allOrders() {
        if (isLoggedIn()) {
            $data = [
                'products' => $this->userModel->getOrdersForUser(getUsername())
            ];

            $this->view('users/order', $data);
        } else {
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages/index');
        }
    }
    //******************************************************** end all order *******************************************************************
}
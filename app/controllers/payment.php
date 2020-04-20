<?php


namespace MVCPHP\controllers;//this name space for autoloader can delete MVCPHP and can get dir for class after backslash



use MVCPHP\libraries\Controller;//this to define class controller in name space MVCPHP\libraries\

/**
 * Class payment
 * @package MVCPHP\controllers
 */
class payment extends Controller
{
    private $paymentModel;
    public function __construct() {
        $this->paymentModel = $this->model('payment');//define vriab
    }
    //******************************************** start add payment and show  *************************************************************
    public function index()
    {
        $this->add();
    }
    /*
     * add and show visa detalis
     * drop dawn in nav bar
     */
    public function add()
    {
        if(isloggedIn())
        {
            //if login
            if($_SERVER['REQUEST_METHOD']=='POST')//if click submit in form in (view/ payment/add)
            {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'payments' => $this->paymentModel->allPayment(getUsername()),//to sho ein table
                    'username' => getUsername(),//fun  in helper dir in  session
                    'visaNum' => trim($_POST['visa']),
                    'pin' => trim($_POST['pin']) ,
                    'money' => trim($_POST['money']) ,
                    'visaNum_err' => '',
                    'pin_err' => '',
                    'money_err' => '',
                ];
                //validate visa Num
                if (empty($data['visaNum'])) {
                    //if input visa num empty
                    $data['visaNum_err'] = "The VISA Number field can't be empty";
                } elseif (strlen($data['visaNum']) != 16) {
                    //visa num should be 16
                    $data['visaNum_err'] = 'The VISA Number must be from 16 number';
                }
                elseif ($this->paymentModel->getPymentByNum($data['visaNum']))
                {
                    //visa num unique if found
                    $data['visaNum_err'] = 'The VISA Number is already exists';
                }

                //validate Pin
                if (empty($data['pin'])) {
                    $data['pin_err'] = 'The PIN field can\'t be empty';
                } elseif ($data['pin'] <= 0) {
                    $data['pin_err'] = 'The PIN can\'t be less then or equal 0';
                }


                  //validate money
                if (empty($data['money'])) {
                    $data['money_err'] = "The PIN field can't be empty";
                } elseif ($data['money'] <= 0 && $data['money'] >= 10000000) {
                    $data['money_err'] = 'The PIN can\'t be less then or equal 0';
                }

                if ( empty($data['visaNum_err']) && empty($data['pin_err']) && empty($data['money_err']) ) {
                    //if not found error
                    if($this->paymentModel->addPayment($data)){
                        //if added in data base successful
                        flash('error', 'Done', 'alert alert-success');
                        redirect('payment/add');
                    } else {
                        //if no added successful
                        flash('error', 'some thing went error', 'alert alert-danger');
                        redirect('pages');
                    }
                } else {
                    $this->view('payment/add', $data);//sent data to view with error
                }
            }
            else
            {
                //if click in nav bar in payment
                $data = [
                    'payments' => $this->paymentModel->allPayment(getUsername()),//if have data will show in table
                    'username' => getUsername(),
                    'visaNum' => '',
                    'pin' => '',
                    'money' => '',
                    'visaNum_err' => '',
                    'pin_err' => '',
                    'money_err' => '',
                ];
                $this->view('payment/add',$data);//sent data to view
            }

        }
        else
        {
            //if not login
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages');
        }
    }
    //******************************************** end add payment and show  **********************************************************

}
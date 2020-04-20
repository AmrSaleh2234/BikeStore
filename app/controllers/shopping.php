<?php


namespace MVCPHP\controllers;//this name space for autoloader can delete MVCPHP and can get dir for class after backslash
use MVCPHP\libraries\Controller;//this to define class controller in name space MVCPHP\libraries\

/**
 *@author team
 * controller manage shopping cart for user
 * have 4 function add ,show ,delete ,update
 * @uses \MVCPHP\libraries\Controller
 */
class shopping extends Controller
{
    private $shopModel;
    private $productModel;
    public function __construct()
    {
        $this->shopModel=$this->model('shop');
        $this->productModel=$this->model('product');
    }
    public function index()//this func main page in website in controller shopping
    {
        $this->show();//fun show all products in shopping cart
    }

    /**
     * add products in shopping cart
     * @param $id<p>
     * id of products in database wich can get from url
     * </p>
     * @return null
     */
    public function add($id)//**************************************** start add cart *******************************************************************
    {
        if(isloggedIn())
        {
            $row=$this->productModel->getProductById($id);
            var_dump($row);

            if ($row)
            {
                //products exist
                if($row[0]->username==getUsername())
                {
                    //item own seller
                    flash('error','this is your item');
                    redirect('pages');
                }
                else
                {


                    if($this->shopModel->insertShoppingCart($id,getUsername()))
                    {
                        //if database insert done
                        flash('error','Done');
                        redirect('pages');
                    }
                    else{
                        //if database can not insert
                        flash('error','this item already exists ','alert alert-danger');
                        redirect('pages');
                    }
                }

            }else
            {
                //if products not found
                flash('error','this products not found');
            }

        }
        else
        {
            //if not not login in website
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages');
        }
    }
    //******************************************************** end add cart *******************************************************************

    //******************************************************** start show  *******************************************************************
    /**
     * show products for user in users/cart or users/index
     * can get in nav bar
     * @param null
    */
    public function show()
    {
        if(isloggedIn())
        {
            $row =$this->shopModel->getAllData(getUsername());//this is func return table cart inner join table products
            $data=[
                'username'=>getUsername(),
                'products'=>$row

            ];
            $this->view('users/cart',$data);
        }
        else
        {
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages/index');
        }
    }
    //******************************************************** end show *******************************************************************

    //******************************************************** start buy ******************************************************************
    /**
     * Buy products and take money from visa/payment
     * @params id int <p>
     * this id products
     * </p>
     * @return null
     */
    public function buy($id) {
        if (isLoggedIn()) {
            if ($this->shopModel->search(getUsername(), $id)) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $data = [
                        'username' => getUsername(),
                        'productId' => $id,
                        'item' => $this->productModel->getProductById($id),
                        'payments' => $this->shopModel->getPaymentsForUsername(getUsername()),
                        'quantity' => $_POST['quantity'],
                        'payment' => $_POST['payment'],
                        'payment_err' => ''
                    ];
                    $neededMoney =  ($data['item'][0]->price) * $data['quantity'];
                    $visaNumber = substr($data['payment'], 0, 16);
                    $visaMoney = substr($data['payment'], 19, -1);
                    if ($data['payment'] == '0') {
                        $data['payment_err'] = 'Please choose paument method';
                    } elseif ($neededMoney > $visaMoney) {
                        $data['payment_err'] = 'There is no enough money';
                    }

                    if (empty($data['payment_err'])) {

                        $visaMoney -= $neededMoney;
                        $data2 = [
                            'username' => getUsername(),
                            'productId' => $id,
                            'newQuantity' => $data['item'][0]->quantity - $_POST['quantity'],
                            'quantity' => $_POST['quantity'],
                            'visaNumber' => $visaNumber,
                            'visaMoney' => $visaMoney
                        ];

                        $this->shopModel->makeOrder($data2);
                        redirect('pages');

                    } else {
                        $this->view('users/buy', $data);
                    }
                } else {
                    $data = [
                        'username' => getUsername(),
                        'item' => $this->productModel->getProductById($id),
                        'payments' => $this->shopModel->getPaymentsForUsername(getUsername())
                    ];
                    $this->view('users/buy', $data);
                }
            } else {
                flash('error', 'Sorry, The Item Is not in your cart', 'alert alert-danger');
                redirect('pages/index');
            }


        } else {
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages/index');
        }
    }//******************************************************** end buy *******************************************************************

}
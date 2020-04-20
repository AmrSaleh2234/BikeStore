<?php


namespace MVCPHP\controllers;
//this name space for autoloader can delete MVCPHP and can get dir for class after backslash


use MVCPHP\libraries\Controller;//this to define class controller in name space MVCPHP\libraries\

/**
 * <b>Class products</b> add , delete ,update  [product ]
 * @package MVCPHP\controllers
 */
class products extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = $this->model('product');
    }

    public function index()
    {
        $this->add();
    }

    /**
     * <b>add</b>add product in database by
     * view/product/add
     */
    public function add()
    {
        if (isLoggedIn()) {
            //logged in

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //post request method
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'username' => getUsername(),
                    'name' => trim($_POST['name']),
                    'feature' => trim($_POST['feature']),
                    'price' => trim($_POST['price']),
                    'quantity' => trim($_POST['quantity']),
                    'photo' => '',
                    'forRent' => trim($_POST['renting']),
                    'isBike' => trim($_POST['isBike']),
                    'isNew' => trim($_POST['new']),
                    'name_err' => '',
                    'feature_err' => '',
                    'price_err' => '',
                    'quantity_err' => '',
                    'photo_err' => '',
                ];

                $photoName = $_FILES['photo']['name'];
                $photoSize = $_FILES['photo']['size'];
                $photoTmp = $_FILES['photo']['tmp_name'];
                $photoType = $_FILES['photo']['type'];

                $photoAllowedExtention = array('jpeg', 'jpg', 'png', 'gif');
                $photoExtention = strtolower(end(explode('.', $photoName)));
                    //validate name
                if (empty($data['name'])) {
                    $data['name_err'] = 'Please fill the name field';
                }
                //validate feature
                if (empty($data['feature'])) {
                    $data['feature_err'] = 'Please fill the feature field';
                }
                //validate quantity
                if (empty($data['quantity'])) {
                    $data['quantity_err'] = 'Please fill the quantity field';
                } elseif ($data['quantity'] <= 0) {
                    $data['quantity_err'] = 'Please fill the quantity field with values more than 0';
                }
                //validate price
                if (empty($data['price']) || $data['price'] <= 0) {
                    $data['price_err'] = 'Please fill the price field';
                } elseif ($data['price'] <= 0 && $data['price']>=99999) {
                    $data['price_err'] = 'Please fill the price field with values more than 0 and less than 99999';
                }
                    //check extension
                if (!in_array($photoExtention, $photoAllowedExtention) && !empty($photoName)) {
                    $data['photo_err'] = 'Sorry, The Extention Not Allowed :(';
                } elseif (empty($photoName)) {
                    $data['photo_err'] = 'Please add a photo for the product';
                }

                if (empty($data['name_err']) && empty($data['feature_err']) && empty($data['quantity_err']) && empty($data['photo_err'])) {
                    //no error
                    $randomNum = rand(0, 100000);
                    move_uploaded_file($photoTmp, 'img/uploads/' . $randomNum . '_' . $photoName);
                    $data['photo'] = $randomNum . '_' . $photoName;
                    if($this->productModel->addProduct($data)) {
                        flash('error', 'Done', 'alert alert-success');

                    } else {
                        //not can add product
                        flash('error', 'something went wrong', 'alert alert-danger');
                    }
                    redirect('pages');

                } else {
                    //view with error
                    $this->view('products/add', $data);
                }
            } else  {
                // not post request method
                $data = [
                    'username' => getUsername(),//this fun exists in helpers dir in session file
                    'name' => '',
                    'feature' => '',
                    'quantity' => '',
                    'forRent' => 1,
                    'isBike' => 1,
                    'isNew' => 1,
                    'name_err' => '',
                    'feature_err' => '',
                    'quantity_err' => '',
                ];
                $this->view('products/add', $data);
            }

        } else {
            // not login
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages/index');
        }
    }
        /**
         *
         */
    public function show() {
        $data = [
            'username' => getUsername(),
            'products' => $this->productModel->allProductsByUsername(getUsername())
        ];
        $this->view('products/show', $data);
    }

    /**
     * @param $id<p>
     * id of product we use to edit product
     * </p>
     */
    public function edit($id) {
        if (isLoggedIn()) {
            //logged in
            $item = $this->productModel->getProductById($id);
            if ($item[0]->username == getUsername()) {
                //if user owner product
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    //if request method by clichk button edit in view
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $data = [
                        'username' => getUsername(),
                        'id' => $id,
                        'name' => trim($_POST['name']),
                        'features' => trim($_POST['feature']),
                        'price' => trim($_POST['price']),
                        'quantity' => trim($_POST['quantity']),
                        'photo' => '',
                        'rentStatus' => trim($_POST['renting']),
                        'isBike' => trim($_POST['isBike']),
                        'isNew' => trim($_POST['isNew']),
                        'name_err' => '',
                        'features_err' => '',
                        'price_err' => '',
                        'quantity_err' => '',
                        'photo_err' => '',
                    ];

                    $photoName = $_FILES['photo']['name'];
                    $photoSize = $_FILES['photo']['name'];
                    $photoTmp = $_FILES['photo']['tmp_name'];
                    $photoType = $_FILES['photo']['type'];

                    $photoAllowedExtention = array('jpeg', 'jpg', 'png', 'gif');
                    $photoExtention = strtolower(end(explode('.', $photoName)));
                    //validate name
                    if (empty($data['name'])) {
                        $data['name_err'] = 'Please fill the name field';
                    }
                    //validate features
                    if (empty($data['features'])) {
                        $data['features_err'] = 'Please fill the feature field';
                    }
                    //validate quantity
                    if (empty($data['quantity'])) {
                        $data['quantity_err'] = 'Please fill the quantity field';
                    } elseif ($data['quantity'] <= 0) {
                        $data['quantity_err'] = 'Please fill the quantity field with values more than 0';
                    }
                    //validate price
                    if (empty($data['price']) || $data['price'] <= 0) {
                        $data['price_err'] = 'Please fill the price field';
                    } elseif ($data['price'] <= 0) {
                        $data['price_err'] = 'Please fill the price field with values more than 0';
                    }
                    //check extension
                    if (!in_array($photoExtention, $photoAllowedExtention) && !empty($photoName)) {
                        $data['photo_err'] = 'Sorry, The Extention Not Allowed :(';
                    }


                    if (empty($data['name_err']) && empty($data['features_err']) && empty($data['quantity_err']) && empty($data['photo_err'])) {
                        //no error
                        if (!empty($photoName)) {
                            $randomNum = rand(0, 100000);
                            move_uploaded_file($photoTmp, 'img/uploads/' . $randomNum . '_' . $photoName);
                            $data['photo'] = $randomNum . '_' . $photoName;
                        }
                        if($this->productModel->editProduct($data)) {
                            flash('error', 'Done', 'alert alert-success');
                        } else {
                            flash('error', 'something went wrong', 'alert alert-danger');

                        }
                        redirect('pages');

                    } else {
                        $this->view('products/edit', $data);
                    }


                } else {
                    $data = [
                        'id' => $id,
                        'name' => $item[0]->name,
                        'features' => $item[0]->features,
                        'price' => $item[0]->price,
                        'quantity' => $item[0]->quantity,
                        'photo' => $item[0]->photoName,
                        'rentStatus' => $item[0]->rentStatus,
                        'isBike' => $item[0]->isBike,
                        'isNew' => $item[0]->isNew,
                        'name_err' => '',
                        'features_err' => '',
                        'price_err' => '',
                        'quantity_err' => '',
                        'photo_err' => '',
                    ];
                    $this->view('products/edit', $data);
                }


            } else {
                flash('error', 'You are not allow to get here', 'alert alert-danger');
                redirect('pages');
            }
        } else {
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages');
        }

    }

    /**
     * @param $id<p>
     * id of product we use to delete product
     * </p>
     */
    public function delete($id) {
        if (isLoggedIn()) {
            $item = $this->productModel->getProductById($id);
            if ($item[0]->username == getUsername()) {
                if($this->productModel->deleteProduct($id)) {
                    redirect('pages');
                } else {
                    flash('error', 'some thing went wrong', 'alert alert-danger');
                    redirect('pages');
                }
            } else {
                flash('error', 'You are not allow to get here', 'alert alert-danger');
                redirect('pages');
            }
        } else {
            flash('error', 'you shoud login in first', 'alert alert-danger');
            redirect('pages');
        }
    }

    /**
     * @param $id<p>
     * id of product we use to rent  product by set value 'isRent '
     * by 1 in database
     * </p>
     */
    public function rent($id) {
        if (isLoggedIn()) {
            //logged in
            $item = $this->productModel->getProductById($id);
            if ($item->rentStatus == 1) {
                //product for rent
                $data = [
                    'username' => getUsername(),
                    'productId' => $id,
                ];
                if($this->productModel->renting($data))
                {
                    flash('error', 'Done', 'alert alert-success');
                }
                else
                {
                    flash('error', 'some thing went wrong ', 'alert alert-danger');
                }

                redirect('pages');

            } else {

                flash('error', 'you are product owner ', 'alert alert-warning');
                redirect('pages/index');
            }
        } else {
            flash('error', 'Sorry, You need to login first', 'alert alert-danger');
            redirect('pages/index');
        }
    }

}
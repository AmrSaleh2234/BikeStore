<?php


namespace MVCPHP\controllers;//this name space for autoloader can delete MVCPHP and can get dir for class after backslash


use MVCPHP\libraries\Controller;//this to define class controller in name space MVCPHP\libraries\

/**
 * <b>Class Posts</b>
 * controller to add delete update posts
 * @package MVCPHP\controllers
 */
class Posts extends Controller
{
    private $postModel;
    public function __construct() {
        $this->postModel = $this->model('Post');
    }
    public function index()
    {
        $this->blog();
    }
//******************************************************** start add post *******************************************************************
    /**
     * <b>add post controller</b> add post in a database and get data by post method in
     * view/posts/add
     */
    public function add() {
        if (isLoggedIn()) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $photo=$_FILES['photo'];
                $data = [
                    'username' => getUsername(),
                    'title' => trim($_POST['title']),
                    'content' => trim($_POST['content']),
                    'photo' => $photo,
                    'title_err' => '',
                    'content_err' => '',
                    'photo_err' => '',
                ];

                $photoName = $photo['name'];
                $photoSize = $photo['name'];
                $photoTmp = $photo['tmp_name'];
                $photoType = $photo['type'];

                $photoAllowedExtention = array('jpeg', 'jpg', 'png', 'gif');
                $photoExtention =explode('.', $photoName);
                $photoExtention = end($photoExtention);
                $photoExtention = strtolower($photoExtention);

                if (empty($data['title'])) {
                    $data['title_err'] = 'Please fill the title field';
                }
                if (empty($data['content'])) {
                    $data['content_err'] = 'Please fill the content field';
                }
                if (!in_array($photoExtention, $photoAllowedExtention) && !empty($photoName)) {
                    $data['photo_err'] = 'Sorry, The Extention Not Allowed :(';
                } elseif (empty($photoName)) {
                    $data['photo_err'] = 'Please add a photo for the product';
                }

                if (empty($data['title_err']) && empty($data['content_err']) && empty($data['photo_err'])) {
                    $randomNum = rand(0, 100000);
                    move_uploaded_file($photoTmp, 'img/uploads/' . $randomNum . '_' . $photoName);
                    $data['photo'] = $randomNum . '_' . $photoName;
                    if($this->postModel->add($data)) {
                        redirect('pages');
                    } else {
                        die('something went wrong');
                    }
                } else {
                    $this->view('posts/add', $data);
                }

            } else {
                $data = [
                    'username' => getUsername(),
                    'title' => '',
                    'content' => '',
                    'photo' => '',
                    'title_err' => '',
                    'content_err' => '',
                    'photo_err' => '',
                ];
                $this->view('posts/add', $data);
            }
        } else {
            die('you need to sign in');
        }
    }//******************************************************** end add post *******************************************************************

//******************************************************** start blog ************************************************************************
    /**
     * <b>blog</b>view all posts from database to
     * view /pages/blog
     */
    public function blog() {
        $data = [
            'posts' => $this->postModel->allPosts()
        ];

        $this->view('pages/blog', $data);
    }
//******************************************************** end blog *******************************************************************

//******************************************************** start show  *******************************************************************
    /**
     * @param $id<p>
     * id for post to get it from database
     * </p>
     */
    public function show($id) {
        $data = [
            'post' => $this->postModel->getPostById($id),
            'comments' => $this->postModel->getPostComments($id),
        ];
        $this->view('posts/show', $data);
    }
    //******************************************************** end show *******************************************************************

//******************************************************** start comment *******************************************************************
    /**
     * <b>comment</b> add comment to specific post
     * @param $id<p>
     * this id for post to add comment to specific post
     *</p>
     */

    public function comment($id) {
        if (isLoggedIn()) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = [
                    'username' => getUsername(),
                    'postId' => $id,
                    'comment' => $_POST['comment'],
                    'comment_err' => '',
                ];
                if (empty($data['comment'])) {
                    $data['comment_err'] = 'Comment can not be empty :(';
                }

                if (empty($data['comment_err'])) {
                    $this->postModel->addComment($data);
                    flash('post-message', 'Comment Seccessfully Added :)');
                    redirect('posts/blog');
                } else {
                    flash('post-message', $data['comment_err'], 'alert alert-danger');
                    redirect('posts/blog');
                }

            } else {
                flash('error', 'you are not allowed to get here', 'alert alert-danger');
                redirect('pages/index');
            }
        } else {
            flash('error', 'you are not allowed you should sign in', 'alert alert-danger');
            redirect('pages/index');
        }
    }
//******************************************************** end comment *******************************************************************
}

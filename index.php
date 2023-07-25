<?php

use Auth\Auth;

//session start
session_start();

define('BASE_PATH', __DIR__);
define('CURRENT_DOMAIN', currentDomain() . '/project/');
define('DISPLAY_ERROR', true);
define('DB_HOST', 'localhost');
define('DB_NAME', 'project');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');


//Mail
define('MAIL_HOST', 'mail.shopoila.ir');
define('SMTP_AUTH', true);
define('MAIL_USERNAME', 'support@shopoila.ir');
define('MAIL_PASSWORD', '!2345Qwe24625');
define('MAIL_PORT', 587);
define('SENDER_MAIL', 'support@shopoila.ir');
define('SENDER_NAME', 'iman admin');





// ------------------------- آدرس دهی به کلاس ها-----------------------

require_once 'database/DataBase.php';
require_once 'database/CreateDB.php';
require_once 'activities/Admin/Admin.php';
require_once 'activities/Admin/Category.php';
require_once 'activities/Admin/Post.php';
require_once 'activities/Admin/Banner.php';
require_once 'activities/Admin/User.php';
require_once 'activities/Admin/Comment.php';
require_once 'activities/Admin/menu.php';
require_once 'activities/Admin/Websetting.php';




//Auth
require_once 'activities/Auth/Auth.php';





// $db = new database\Database();
// $db = new database\CreateDB();
// $db->run();


spl_autoload_register(function ($className) {
    $path = BASE_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    include $path . $className . '.php';
});


// $auth = new Auth();
// $auth->sendMail('gipcenter3@gmail.com', 'in email test2', '<p>in email testi ersal shodeh test2</p>');

// ------------------------- سیستم روتینگ -----------------------


// uri('admin/category', 'Category', 'index');
// uri('admin/category/store', 'Category', 'store', 'POST');


function uri($reservedUrl, $class, $method, $requestMethod = 'GET')
{

    //-------------- current url array
    $currentUrl = explode('?', currentUrl())[0];
    $currentUrl = str_replace(CURRENT_DOMAIN, '', $currentUrl);
    $currentUrl = trim($currentUrl, '/');
    $currentUrlArray = explode('/', $currentUrl);
    $currentUrlArray = array_filter($currentUrlArray);


    //-------------- reserved Url array
    $reservedUrl = trim($reservedUrl, '/');
    $reservedUrlArray = explode('/', $reservedUrl);
    $reservedUrlArray = array_filter($reservedUrlArray);


    if (sizeof($currentUrlArray) != sizeof($reservedUrlArray) || methodField() != $requestMethod) {
        return false;
    }

    $parameters = [];
    for ($key = 0; $key < sizeof($currentUrlArray); $key++) {
        if ($reservedUrlArray[$key][0] == "{" && $reservedUrlArray[$key][strlen($reservedUrlArray[$key]) - 1] == "}") {
            array_push($parameters, $currentUrlArray[$key]);
        } elseif ($currentUrlArray[$key] !== $reservedUrlArray[$key]) {
            return false;
        }
    }
    // ---------------------- هر اطلاعاتی که کاربر با فرم ارسال می کند را میریزد در $request
    if (methodField() == 'POST') {
        $request = isset($_FILES) ? array_merge($_POST, $_FILES) : $_POST;
        $parameters = array_merge([$request], $parameters);
    }

    $object = new $class;
    call_user_func_array(array($object, $method), $parameters);
    exit();
}
// admin/category/edit/{id} reserved url
// admin/category/delete/{id} reserved url

// admin/category/edit/5 current url
// uri('admin/category', 'Category', 'index');




//----------Helpers------------

function protocol()
{
    return stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
}


//پروتکل و نام دامنه را کنار هم قرار میدهد(HTTPS || HTTP)
function currentDomain()
{
    return protocol() . $_SERVER['HTTP_HOST'];
}


function asset($src)
{
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $src = $domain . '/' . trim($src, '/ ');
    return $src;
}


function url($url)
{
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $url = $domain . '/' . trim($url, '/ ');
    return $url;
}



function currentUrl()
{
    return currentDomain() . $_SERVER['REQUEST_URI'];
}


function methodField()
{
    return $_SERVER['REQUEST_METHOD'];
}




function displayError($displayError)
{

    if ($displayError) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }
}
displayError(DISPLAY_ERROR);




// این کد، یک سیستم پیام‌های فلش (Flash Messages) برای استفاده در برنامه PHP شما ایجاد می‌کند. این سیستم به شما امکان می‌دهد تا پیام‌های کوتاه و موقتی را بین صفحات خود منتقل کنید. به طور کلی، این سیستم به شما اجازه می‌دهد تا پیام‌هایی را که باید به کاربر نشان دهید، به یک بخش از حافظه‌ی موقت (Session) ذخیره کنید تا در صفحات بعدی به راحتی قابل دسترسی باشد.
global $flashMessage;
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}


function flash($name, $value = null)
{
    if ($value === null) {
        global $flashMessage;
        $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
        return $message;
    } else {
        $_SESSION['flash_message'][$name] = $value;
    }
}
// flash('login_error', 'ورود با خطا مواجه شد');
// flash('cart_success', 'محصول با موفقیت به سبد خرید شما اضافه شد');
// echo flash('login_error');
// echo flash('cart_success');


function dd($var)
{
    echo '<pre>';
    var_dump($var);
    exit;
}



// ------------------------- رزرو مسیر سیستم روتینگ -----------------------

// ------------ Category ---------
uri('admin/category', 'Admin\Category', 'index');
uri('admin/category/create', 'Admin\Category', 'create');
uri('admin/category/store', 'Admin\Category', 'store', 'POST');
uri('admin/category/edit/{id}', 'Admin\Category', 'edit');
uri('admin/category/update/{id}', 'Admin\Category', 'update', 'POST');
uri('admin/category/delete/{id}', 'Admin\Category', 'delete');


// ------------ Post ---------
uri('admin/post', 'Admin\Post', 'index');
uri('admin/post/create', 'Admin\Post', 'create');
uri('admin/post/store', 'Admin\Post', 'store', 'POST');
uri('admin/post/edit/{id}', 'Admin\Post', 'edit');
uri('admin/post/update/{id}', 'Admin\Post', 'update', 'POST');
uri('admin/post/delete/{id}', 'Admin\Post', 'delete');

uri('admin/post/selected/{id}', 'Admin\Post', 'selected');
uri('admin/post/breaking-news/{id}', 'Admin\Post', 'breakingNews');


// ------------ Banner ---------
uri('admin/banner', 'Admin\Banner', 'index');
uri('admin/banner/create', 'Admin\Banner', 'create');
uri('admin/banner/store', 'Admin\Banner', 'store', 'POST');
uri('admin/banner/edit/{id}', 'Admin\Banner', 'edit');
uri('admin/banner/update/{id}', 'Admin\Banner', 'update', 'POST');
uri('admin/banner/delete/{id}', 'Admin\Banner', 'delete');

uri('admin/banner/selected/{id}', 'Admin\Banner', 'selected');



// ------------ User ---------
uri('admin/user', 'Admin\User', 'index');
uri('admin/user/edit/{id}', 'Admin\User', 'edit');
uri('admin/user/update/{id}', 'Admin\User', 'update', 'POST');
uri('admin/user/delete/{id}', 'Admin\User', 'delete');
uri('admin/user/permission/{id}', 'Admin\User', 'permission');



// ------------ Comment ---------
uri('admin/comment', 'Admin\Comment', 'index');
uri('admin/comment/status/{id}', 'Admin\Comment', 'status');
uri('admin/comment/show/{id}', 'Admin\Comment', 'show');




// ------------ Menu ---------
uri('admin/menu', 'Admin\Menu', 'index');
uri('admin/menu/create', 'Admin\Menu', 'create');
uri('admin/menu/store', 'Admin\Menu', 'store', 'POST');
uri('admin/menu/edit/{id}', 'Admin\Menu', 'edit');
uri('admin/menu/update/{id}', 'Admin\Menu', 'update', 'POST');
uri('admin/menu/delete/{id}', 'Admin\Menu', 'delete');





// ------------ Websetting ---------
uri('admin/websetting', 'Admin\Websetting', 'index');
uri('admin/websetting/edit', 'Admin\Websetting', 'edit');
uri('admin/websetting/update', 'Admin\Websetting', 'update', 'POST');



//Auth
uri('register', 'Auth\Auth', 'Register');
uri('register/store', 'Auth\Auth', 'RegisterStore', 'POST');
uri('activation/{veryfy_token}', 'Auth\Auth', 'activation',);

uri('login', 'Auth\Auth', 'login');
uri('check-login', 'Auth\Auth', 'checkLogin', 'POST');
uri('logout', 'Auth\Auth', 'logOut');

uri('forgot', 'Auth\Auth', 'forGot');
uri('forgot/request', 'Auth\Auth', 'forgotRequest', 'POST');
uri('reset-password-form/{forgot_token}', 'Auth\Auth', 'resetPasswordView');


echo '404 - Page Not Found';

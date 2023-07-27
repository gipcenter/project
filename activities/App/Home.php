<?php

namespace App;

class Home
{
    public function index()
    {
        require_once(BASE_PATH . '/template/app/index.php');
    }


    public function show($id)
    {
        require_once(BASE_PATH . '/template/app/show.php');
    }


    public function category($id)
    {
        require_once(BASE_PATH . '/template/app/category.php');
    }


    public function commentStore($request)
    {
    }

    protected function redirectBack()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

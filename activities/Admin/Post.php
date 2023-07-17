<?php

namespace Admin;

use database\DataBase;

class Post extends Admin
{

    public function index()
    {
        $db = new DataBase();
        $posts = $db->select("SELECT
                                posts.*,
                                categories.name AS category_name,
                                users.username AS user_name
                            FROM
                                posts
                            LEFT JOIN categories ON posts.cat_id = categories.id
                            LEFT JOIN users ON posts.user_id = users.id;")->fetchAll();

        require_once(BASE_PATH . '/template/admin/posts/index.php');
    }

    public function create()
    {
        $db = new DataBase();
        $categories = $db->select('SELECT * FROM categories ORDER BY `id` ASC');
        require_once(BASE_PATH . '/template/admin/posts/create.php');
    }


    public function store($request)
    {
        $db = new DataBase();
        $db->insert('posts', array_keys($request), $request);
        $this->redirect('admin/post');
    }


    public function edit($id)
    {
        $db = new DataBase();
        $categories = $db->select('SELECT * FROM categories ORDER BY `id` ASC');
        $post = $db->select('SELECT * FROM posts WHERE id = ?;', [$id])->fetch();
        require_once(BASE_PATH . '/template/admin/posts/edit.php');
    }

    public function update($request, $id)
    {
        $db = new DataBase();
        $db->update('posts', $id, array_keys($request), $request);
        $this->redirect('admin/post');
    }


    public function delete($id)
    {
        $db = new DataBase();
        $db->delete('posts', $id);
        $this->redirect('admin/post');
    }
}
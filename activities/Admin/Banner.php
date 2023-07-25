<?php

namespace Admin;

use database\DataBase;


class Banner extends Admin
{

    public function index()
    {
        $db = new DataBase();
        $banners = $db->select("SELECT * FROM `banners` ORDER BY id DESC;")->fetchAll();
        require_once(BASE_PATH . '/template/admin/banners/index.php');
    }

    public function create()
    {
        $db = new DataBase();
        require_once(BASE_PATH . '/template/admin/banners/create.php');
    }


    public function store($request)
    {
        $db = new DataBase();
        if ($request['url'] != null) {
            $request['image'] = $this->saveImage($request['image'], 'banner-image', pathinfo($request['image']['name'], PATHINFO_FILENAME));
            if ($request['image']) {
                $db->insert('banners', array_keys($request), $request);

                $this->redirect('admin/banner');
            } else {
                $this->redirect('admin/banner');
            }
        } else {
            $this->redirect('admin/banner');
        }
    }

    public function edit($id)
    {
        $db = new DataBase();
        $banner = $db->select('SELECT * FROM banners WHERE id = ?;', [$id])->fetch();
        require_once(BASE_PATH . '/template/admin/banners/edit.php');
    }



    public function update($request, $id)
    {

        $db = new DataBase();
        if ($request['url'] != null) {
            if ($request['image']['tmp_name'] != null) {
                $banner = $db->select('SELECT * FROM banners WHERE id = ?;', [$id])->fetch();
                $this->removeImage($banner['image']);
                $request['image'] = $this->saveImage($request['image'], 'banner-image', pathinfo($request['image']['name'], PATHINFO_FILENAME));
            } else {
                unset($request['image']);
            }
            $db->update('banners', $id, array_keys($request), $request);
            $this->redirect('admin/banner');
        } else {
            $this->redirect('admin/banner');
        }
    }




    public function delete($id)
    {
        $db = new DataBase();
        $banner = $db->select('SELECT * FROM banners WHERE id = ?;', [$id])->fetch();
        $this->removeImage($banner['image']);
        $db->delete('banners', $id);
        $this->redirectBack();
    }


    public function selected($id)
    {
        $db = new DataBase();
        $banner = $db->select('SELECT * FROM banners WHERE id = ?;', [$id])->fetch();

        if (empty($banner)) {
            $this->redirectBack();
        }
        if ($banner['selected'] == 1) {
            $db->update('banners', $id, ['selected'], [2]);
        } else {
            $db->update('banners', $id, ['selected'], [1]);
        }
        $this->redirectBack();
    }
}

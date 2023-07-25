<?php

namespace Admin;

use database\DataBase;

class Websetting extends Admin
{

    public function index()
    {
        $db = new DataBase();
        $websetting = $db->select('SELECT * FROM setting')->fetch();
        require_once(BASE_PATH . '/template/admin/websetting/index.php');
    }
    public function edit()
    {
        $db = new DataBase();
        $websetting = $db->select('SELECT * FROM setting')->fetch();
        require_once(BASE_PATH . '/template/admin/websetting/edit.php');
    }

    public function update($request)
    {
        $db = new DataBase();
        $websetting = $db->select('SELECT * FROM setting')->fetch();

        if ($request['logo']['tmp_name'] != '') {
            $request['logo'] = $this->saveImage($request['logo'], 'set-image', 'logo');
        } else {
            unset($request['logo']);
        }
        if ($request['icon']['tmp_name'] != '') {
            $request['icon'] = $this->saveImage($request['icon'], 'set-image', 'icon');
        } else {
            unset($request['icon']);
        }
        if (!empty($websetting)) {
            $db->update('setting', $websetting['id'], array_keys($request), $request);
        } else {
            $db->insert('setting', array_keys($request), $request);
        }
        $this->redirect('admin/websetting');
    }
}

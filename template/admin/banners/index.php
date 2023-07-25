<?php

require_once(BASE_PATH . '/template/admin/layouts/header.php');


?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h5"><i class="fas fa-image"></i> Banner</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a role="button" href="<?= url('admin/banner/create/') ?>" class="btn btn-sm btn-success">create</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <caption>List of banners</caption>
        <thead>
            <tr>
                <th>#</th>
                <th>url</th>
                <th>Selected</th>
                <th>image</th>
                <th>setting</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $i = 1;
            foreach ($banners as $banner) {
            ?>
                <tr>
                    <td>
                        <?= $i++ ?>
                    </td>

                    <td>
                        <?= $banner['url'] ?>
                    </td>

                    <td>
                        <?= $banner['selected'] == 1 ? '<span class="badge badge-success">Published</span>' : '<span class="badge badge-dark">UnPublished</span>' ?>
                    </td>
                    <td><img style="width: 80px;" src="<?= asset($banner['image']) ?>" alt=""></td>
                    <td>
                        <a role="button" class="btn btn-sm btn-primary text-white" href="<?= url('admin/banner/edit/' . $banner['id']) ?>">edit</a>
                        <a role="button" class="btn btn-sm btn-danger text-white" href="<?= url('admin/banner/delete/' . $banner['id']) ?>">delete</a>
                        <a role=" button" class=" btn btn-sm btn-warning text-dark" href="<?= url('admin/banner/selected/' . $banner['id']) ?>">
                            <?= $banner['selected'] == 1 ? 'UnPublished' : 'Published' ?>
                        </a>
                    </td>
                </tr>

            <?php } ?>

        </tbody>

    </table>
</div>

<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php');


?>
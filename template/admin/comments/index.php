<?php

require_once(BASE_PATH . '/template/admin/layouts/header.php');


?>




<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h5"><i class="fas fa-newspaper"></i> Comments</h1>
</div>
<section class="table-responsive">
    <table class="table table-striped table-sm">
        <caption>List of comments</caption>
        <thead>
            <tr>
                <th>#</th>
                <th>user ID</th>
                <th>post ID</th>
                <th>comment</th>
                <th>status</th>
                <th>setting</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($comments as $comment) { ?>
                <tr>
                    <td>
                        <?= $i++ ?>
                        </a>
                    </td>
                    <td>
                        <?= $comment['username'] ?>
                    </td>
                    <td>
                        <?= substr($comment['post_title'], 0, 40) . "..." ?>
                    </td>
                    <td>
                        <a href="<?= url('admin/comment/show/' . $comment['id']) ?>" class="btn btn-link"><?= substr($comment['comment'], 0, 40) . "..." ?></a>
                    </td>
                    <td>
                        <?= $comment['status'] ?>
                    </td>
                    <td>
                        <?php if ($comment['status'] == 'seen') { ?>
                            <a role="button" class="btn btn-sm btn-success text-white" href="<?= url('admin/comment/status/' . $comment['id']) ?>">click to approved</a>
                        <?php } else { ?>
                            <a role="button" class="btn btn-sm btn-warning text-white" href="<?= url('admin/comment/status/' . $comment['id']) ?>">click not to approved</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php');


?>
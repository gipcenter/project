<?php

require_once(BASE_PATH . '/template/admin/layouts/header.php');


?>
<section class="pt-3 pb-1 mb-2 border-bottom ">
    <h1 class="h5 ">Show Comment</h1>
</section>
<section class="row my-3 ">
    <section class="col-12 ">
        <h1 class="h4 border-bottom ">User : </h1>
        <p class="text-secondary border-bottom ">Post : <span class="text-dark"><?= $show['post_title'] ?></span></p>
        <p class="text-secondary border-bottom ">Comment : <span class="text-dark"><?= $show['comment'] ?></span></p>
        <p class="text-secondary border-bottom ">Status : <span class="text-dark"><?= $show['status'] ?></span></p>
        <p class="text-secondary border-bottom ">Created : <span class="text-dark"><?= $show['created_at'] ?></span></p>
        <p class="text-secondary border-bottom ">updated : <span class="text-dark"><?= $show['updated_at'] ?></span></p>

        <?php if ($show['status'] == 'seen') { ?>
            <a role="button" class="btn btn-sm btn-success text-white" href="<?= url('admin/comment/status/' . $show['id']) ?>">click to approved</a>
        <?php } else { ?>
            <a role="button" class="btn btn-sm btn-warning text-white" href="<?= url('admin/comment/status/' . $show['id']) ?>">click not to approved</a>
        <?php } ?>

    </section>
</section>
<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php');


?>
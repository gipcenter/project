<?php

require_once(BASE_PATH . '/template/admin/layouts/header.php');


?>

<section class="pt-3 pb-1 mb-2 border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <h1 class="display-6 text-center mb-3"><?= $showPost['title'] ?></h1>
                <div class="lead text-justify"><?= $showPost['summary'] ?></div>
                <hr>
                <div class="row">
                    <div class="text-justify col-md-8"><?= $showPost['body'] ?></div>
                    <div class="col-md-4">
                        <a href="<?= asset($showPost['image']) ?>" data-lightbox="<?= $showPost['title'] ?>" data-title="<?= $showPost['summary'] ?>">
                            <img src="<?= asset($showPost['image']) ?>" alt="<?= $showPost['title'] ?>" class="img-fluid rounded shadow" style="max-width: 100%;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
</section>


<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php');


?>
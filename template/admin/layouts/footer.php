</main>
</div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="<?= asset('public/admin-panel/js/bootstrap.min.js') ?>"></script>
<script src="<?= asset('public/admin-panel/js/mdb.min.js') ?>"></script>
<script src="<?= asset('public/ckeditor/ckeditor.js') ?>"></script>

<script>
// $(document).ready(function() {
//     CKEDITOR.replace('summary');
//     CKEDITOR.replace('body');
// })
ClassicEditor
    .create(document.querySelector('#summary'))
    .catch(error => {
        console.error(error);
    });
ClassicEditor
    .create(document.querySelector('#body'))
    .catch(error => {
        console.error(error);
    });
</script>

</body>

</html>
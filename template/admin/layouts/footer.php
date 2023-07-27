</main>
</div>
</div>


<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script> -->

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://unpkg.com/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
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


    $('#published_at').datetimepicker({
        dateFormat: "yyyy-mm-dd",
        timeFormat: "HH:mm:ss",
        // onSelect: function(dateText, inst) {
        //     var selectedDate = new Date(dateText);
        //     var timestamp = selectedDate.getTime() / 1000;
        //     $('#published_at').val(timestamp);
        // }
    });
    //         console.log($('#published_at').val('timestamp'));


    // $("#published_at_view").datepicker({

    // });



    // $("#published_at_view").datepicker({
    //     onSelect: function(dateText, inst) {
    //         var selectedDate = new Date(dateText);
    //         var timestamp = selectedDate.getTime() / 1000; // تقسیم بر 1000 برای تبدیل میلی ثانیه به ثانیه
    //         $("#published_at").val(timestamp);
    //     }
    // });
</script>

</body>

</html>
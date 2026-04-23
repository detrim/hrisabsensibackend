<script>
    function toGambar() {
        $(".inGambar").click();
    }

    function toImage() {
        $(".inImage").click();
    }


    function previewFoto(input) {
        let reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toText(textImg) {
        const newText = textImg.replace(/^.*\\/, "");
        $('.inText').text(newText);
        $('#selected').text('logo');
        const sampul = document.querySelector('#photos');
        const imgPreview = document.querySelector('.img-preview');
        const fileSampul = new FileReader();
        fileSampul.readAsDataURL(sampul.files[0]);
        fileSampul.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }


    $(document).on('click', '.btn-delete', function() {
        $(this).closest('.pendidikan-row').remove();
    });


    /* FOTO PREVIEW */
    function previewFoto(event) {
        let reader = new FileReader();
        reader.onload = function() {
            document.getElementById('previewFoto').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    /* USIA */
    $(document).on('change', '#tgl_lahir', function() {

        let tgl = new Date(this.value);
        let now = new Date();

        let umur = now.getFullYear() - tgl.getFullYear();

        let m = now.getMonth() - tgl.getMonth();
        if (m < 0 || (m === 0 && now.getDate() < tgl.getDate())) {
            umur--;
        }
        $('#usia_text').val(umur + " Tahun");
        $('#usia').val(umur);
    }); /* TOGGLE PENDIDIKAN */
    function togglePendidikan(cb) {
        let row = cb.closest('.row');
        let
            select = row.querySelector('select');
        let input = row.querySelector('input[type="text" ]');
        let
            input2 = row.querySelector('input[type="number" ]');
        if (cb.checked) {
            select.disabled = false;
            input.disabled = false;
            input2.disabled = false;
        } else {
            select.disabled = true;
            input.disabled = true;
            input2.disabled = true;
            input.value = "";
            input2.value = "";
        }
    }
</script>

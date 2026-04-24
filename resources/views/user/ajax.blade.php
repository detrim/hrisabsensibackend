@push('script-user')
    <script>
        // ===============================
        // 1. AUTOSUGGEST NAMA PEGAWAI
        // ===============================
        $('#nama_pengguna').on('keyup', function() {
            let query = $(this).val();

            if (query.length < 2) {
                $('#suggestion-box').html('');
                return;
            }

            $.ajax({
                url: "/api/pegawaisearch",
                type: "GET",
                headers: {
                    "Authorization": "Bearer {{ session('api_token') }}"
                },
                data: {
                    q: query
                },
                success: function(data) {

                    let html = '';

                    data.forEach(function(item) {
                        html += `
                        <a href="#"
                        class="list-group-item list-group-item-action ${item.disabled ? 'disabled text-muted' : ''}"
                        data-id="${item.id}"
                        data-name="${item.nama}"
                        style="${item.disabled ? 'pointer-events:none; opacity:1;' : ''}">

                            ${item.nama}
                            ${item.disabled ? '<small class="text-danger ms-1">(sudah digunakan)</small>' : ''}
                        </a>
                    `;
                    });

                    $('#suggestion-box').html(html);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        // pilih suggestion
        $(document).on('click', '.list-group-item', function(e) {
            e.preventDefault();

            $('#nama_pengguna').val($(this).data('name'));
            $('#pegawai_id').val($(this).data('id'));
            $('#suggestion-box').html('');
        });


        // ===============================
        // 2. USERNAME VALIDATION
        // ===============================
        $('#username').on('keyup', function() {
            let val = $(this).val();
            let regex = /^[a-z0-9]+$/;

            if (val.length < 5) {
                $('#username_msg').text("Minimal 5 karakter");
                return;
            }

            if (val.indexOf(' ') >= 0) {
                $('#username_msg').text("Tidak boleh ada spasi");
                return;
            }

            if (!regex.test(val)) {
                $('#username_msg').text("Hanya huruf kecil & angka");
                return;
            }

            $('#username_msg').text("Checking...");

            $.ajax({
                url: "/api/checkusername",
                type: "GET",
                headers: {
                    "Authorization": "Bearer {{ session('api_token') }}"
                },
                data: {
                    username: val
                },
                success: function(res) {
                    if (res.exists) {
                        $('#username_msg').text("Username sudah digunakan ❌");
                    } else {
                        $('#username_msg').text("Username tersedia ✅");
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        $(document).ready(function() {
            $('#userTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                ordering: true
            });
        });
        // 3. AUTO GENERATE PASSWORD
        $('#btn-generate').on('click', function() {
            console.log(1);
            let newPass = generatePassword();
            $('#password').val(newPass);

            $('#confirm_msg').text('');
            $('#password_msg').text('Password berhasil digenerate');
        });

        function generatePassword() {
            let chars = "ABCDEFGHijklmnopqrstuvwxyz0123456789!@#$%";
            let pass = "";

            for (let i = 0; i < 10; i++) {
                pass += chars[Math.floor(Math.random() * chars.length)];
            }

            return pass;
        }

        $('#password').val(generatePassword());


        // ===============================
        // 4. PASSWORD VALIDATION
        // ===============================
        $('#password').on('keyup', function() {
            let val = $(this).val();

            let regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

            if (val.indexOf(' ') >= 0) {
                $('#password_msg').text("Tidak boleh ada spasi");
            } else if (!regex.test(val)) {
                $('#password_msg').text("Minimal 8 karakter, ada huruf besar, kecil, & simbol");
            } else {
                $('#password_msg').text("");
            }
        });


        // ===============================
        // 5. CONFIRM PASSWORD
        // ===============================
        $('#password_confirm').on('keyup', function() {
            if ($(this).val() !== $('#password').val()) {
                $('#confirm_msg').text("Password tidak sama");
            } else {
                $('#confirm_msg').text("");
            }
        });
    </script>
@endpush

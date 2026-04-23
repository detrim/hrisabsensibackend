<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'required' => 'Kolom :attribute wajib diisi.',
    'numeric' => 'Kolom :attribute harus berupa angka.',
    'integer' => 'Kolom :attribute harus berupa angka bulat.',
    'email' => 'Format :attribute tidak valid.',
    'string' => 'Kolom :attribute harus berupa teks.',
    'regex' => 'Format :attribute tidak valid.',
    'unique' => ':attribute sudah digunakan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',

    'min' => [
        'numeric' => ':attribute minimal :min.',
        'string' => ':attribute minimal :min karakter.',
        'file' => ':attribute minimal :min KB.',
    ],

    'max' => [
        'numeric' => ':attribute maksimal :max.',
        'string' => ':attribute maksimal :max karakter.',
        'file' => ':attribute maksimal :max KB.',
    ],

    'digits_between' => ':attribute harus antara :min sampai :max digit.',

    'image' => ':attribute harus berupa gambar.',
    'mimes' => ':attribute harus bertipe: :values.',

    'date' => ':attribute harus berupa tanggal yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name' => 'Nama',
        'email' => 'Email',
        'password' => 'Password',
        'username' => 'Username',
        'role' => 'Role',
        'captcha' => 'Captcha',

        'nip' => 'NIP',
        'nama' => 'Nama',
        'no_hp' => 'Nomor HP',
        'tempat_lahir' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'tanggal_masuk' => 'Tanggal Masuk',
        'jumlah_anak' => 'Jumlah Anak',
        'status_kawin' => 'Status Kawin',
        'alamat_lengkap' => 'Alamat Lengkap',
        'jenis_kelamin' => 'Jenis Kelamin',
        'status_pegawai' => 'Status Pegawai',
        'departemen' => 'Departemen',
        'jabatan' => 'Jabatan',
        'pendidikan' => 'Pendidikan',
        'status' => 'Status',
        'foto' => 'Foto',
    ],

];

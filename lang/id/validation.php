<?php
// resources/lang/id/validation.php
return [
    'required' => ':attribute wajib diisi.',
    'max' => [
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'custom' => [
        'code' => [
            'required' => 'Kode outlet wajib diisi.',
            'max' => 'Kode outlet tidak boleh lebih dari 255 karakter.',
        ],
        'name' => [
            'required' => 'Nama outlet wajib diisi.',
            'max' => 'Nama outlet tidak boleh lebih dari 255 karakter.',
        ],
        'owner' => [
            'required' => 'Nama pemilik outlet wajib diisi.',
            'max' => 'Nama pemilik outlet tidak boleh lebih dari 255 karakter.',
        ],
        'telp' => [
            'required' => 'Nomor telepon wajib diisi.',
            'max' => 'Nomor telepon tidak boleh lebih dari 255 karakter.',
        ],
        'address' => [
            'required' => 'Alamat wajib diisi.',
            'max' => 'Alamat tidak boleh lebih dari 255 karakter.',
        ],
        'latlong' => [
            'required' => 'Koordinat (LatLong) wajib diisi.',
            'max' => 'Koordinat (LatLong) tidak boleh lebih dari 255 karakter.',
        ],
        'district' => [
            'required' => 'Kecamatan wajib diisi.',
            'max' => 'Kecamatan tidak boleh lebih dari 255 karakter.',
        ],
        'radius' => [
            'required' => 'Radius wajib diisi.',
            'max' => 'Radius tidak boleh lebih dari 255 karakter.',
        ],
        'limit' => [
            'required' => 'Limit wajib diisi.',
            'max' => 'Limit tidak boleh lebih dari 255 karakter.',
        ],
    ],
    'attributes' => [
        'code' => 'Kode Outlet',
        'name' => 'Nama Outlet',
        'owner' => 'Pemilik Outlet',
        'telp' => 'Nomor Telepon',
        'address' => 'Alamat',
        'latlong' => 'Koordinat (LatLong)',
        'district' => 'Kecamatan',
        'radius' => 'Radius',
        'limit' => 'Limit',
    ],
];

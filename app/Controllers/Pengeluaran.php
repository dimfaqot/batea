<?php

namespace App\Controllers;

class Pengeluaran extends BaseController
{
    public function index(): string
    {
        $data = db(menu()['tabel'])->where('lokasi', user()['lokasi'])->orderBy("updated_at", "DESC")->get()->getResultArray();
        return view(menu()['controller'] . '/' . menu()['controller'] . "_" . 'landing', ['judul' => menu()['menu'], "data" => $data]);
    }
    public function add()
    {
        $barang_id = clear($this->request->getVar('barang_id'));
        $harga = angka_to_int(clear($this->request->getVar('harga')));
        $qty = angka_to_int(clear($this->request->getVar('qty')));
        $diskon = angka_to_int(clear($this->request->getVar('diskon')));
        $pj = upper_first(clear($this->request->getVar('pj')));

        $db = \Config\Database::connect();
        $db->transStart();

        $barang = db('barang')->where('id', $barang_id)->get()->getRowArray();

        if ($diskon > ($harga * $qty)) {
            gagal(base_url(menu()['controller']), "Diskon over");
        }
        if (!$barang) {
            gagal(base_url(menu()['controller']), "Barang not found");
        }

        $input = [

            'tgl' => time(),
            'jenis' => $barang['jenis'],
            'barang' => $barang['barang'],
            'barang_id' => $barang['id'],
            'harga'       => angka_to_int(clear($this->request->getVar('harga'))),
            'qty'       => $qty,
            'total'       => $harga * $qty,
            'diskon'       => $diskon,
            'biaya'       => ($harga * $qty) - $diskon,
            'pj'       => $pj,
            'petugas'       => user()['nama'],
            'lokasi'       => user()['lokasi'],
            'updated_at'       => time()
        ];

        if ($barang['jenis'] !== "Kulakan") {
            $barang['qty'] += (int)$input['qty'];
            db('barang')->where('id', $barang['id'])->update($barang);
        }

        // Simpan data  
        db(menu()['tabel'])->insert($input);

        $db->transComplete();

        return $db->transStatus()
            ? sukses(base_url(menu()['controller']), 'Sukses')
            : gagal(base_url(menu()['controller']), 'Gagal');
    }

    public function edit()
    {
        $id = clear($this->request->getVar('id'));
        $barang_id = clear($this->request->getVar('barang_id'));
        $harga = angka_to_int(clear($this->request->getVar('harga')));
        $qty = angka_to_int(clear($this->request->getVar('qty')));
        $diskon = angka_to_int(clear($this->request->getVar('diskon')));
        $pj = upper_first(clear($this->request->getVar('pj')));

        $q = db(menu()['tabel'])->where('id', $id)->get()->getRowArray();

        if (!$q) {
            gagal(base_url(menu()['controller']), "Id not found");
        }

        $barang = db('barang')->where('id', $barang_id)->get()->getRowArray();

        if (!$barang) {
            gagal(base_url(menu()['controller']), "Barang not found");
        }
        if ($diskon > ($harga * $qty)) {
            gagal(base_url(menu()['controller']), "Diskon over");
        }


        $q = [
            'jenis' => $barang['jenis'],
            'barang' => $barang['barang'],
            'barang_id' => $barang['id'],
            'harga'       => angka_to_int(clear($this->request->getVar('harga'))),
            'qty'       => $qty,
            'total'       => $harga * $qty,
            'diskon'       => $diskon,
            'biaya'       => ($harga * $qty) - $diskon,
            'pj'       => $pj,
            'updated_at' => time()
        ];

        // Simpan data
        db(menu()['tabel'])->where('id', $id)->update($q)
            ? sukses(base_url(menu()['controller']), 'Sukses')
            : gagal(base_url(menu()['controller']), 'Gagal');
    }
}

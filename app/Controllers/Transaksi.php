<?php

namespace App\Controllers;

class Transaksi extends BaseController
{
    function __construct()
    {
        if (!session('id')) {
            session()->setFlashdata('gagal', "Ligin first");
            header("Location: " . base_url());
            die;
        }
    }
    public function index(): string
    {

        $data = db(menu()['tabel'])->where('lokasi', user()['lokasi'])->orderBy("tgl", "DESC")->get()->getResultArray();
        return view(menu()['controller'] . '/' . menu()['controller'] . "_" . 'landing', ['judul' => menu()['menu'], "data" => $data]);
    }

    public function pembayaran()
    {
        $super_total = json_decode(json_encode($this->request->getVar('super_total')), true);
        $datas = json_decode(json_encode($this->request->getVar('datas')), true);
        $uang = angka_to_int(clear($this->request->getVar('uang')));

        if ($uang < $super_total['biaya']) {
            gagal_js("Uang kurang");
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $no_nota = next_invoice();

        $tgl = time();

        foreach ($datas as $i) {
            if (!$db->table('transaksi')->insert([
                "tgl" => $tgl,
                "jenis" => $i['jenis'],
                "barang" => $i['barang'],
                "barang_id" => $i['id'],
                "harga" => $i['harga'],
                "qty" => $i['qty'],
                "total" => $i['total'],
                "diskon" => $i['diskon'],
                "biaya" => $i['biaya'],
                "petugas" => user()['nama'],
                "lokasi" => user()['lokasi']
            ])) {
                gagal_js("Insert transaksi gagal");
            }

            $barang = db('barang')->where('id', $i['id'])->get()->getRowArray();
            if (!$barang) {
                gagal_js("Id " . $i['barang'] . " not found");
            }

            $barang['qty'] -= (int)$i['qty'];
            if (!db('barang')->where('id', $barang['id'])->update($barang)) {
                gagal_js("Update stok gagal");
            }

            if (!$db->table('nota')->insert([
                "no_nota" => $no_nota,
                "tgl" => $tgl,
                "jenis" => $i['jenis'],
                "barang" => $i['barang'],
                "barang_id" => $i['id'],
                "harga" => $i['harga'],
                "qty" => $i['qty'],
                "total" => $i['total'],
                "diskon" => $i['diskon'],
                "biaya" => $i['biaya'],
                "petugas" => user()['nama'],
                "lokasi" => user()['lokasi'],
                "uang" => $uang,
            ])) {
                gagal_js("Insert nota gagal");
            }
        }


        $db->transComplete();

        return $db->transStatus()
            ? sukses_js("Sukses", str_replace("/", "-", $no_nota))
            : gagal_js("Gagal");
    }
    public function add_hutang()
    {
        $datas = json_decode(json_encode($this->request->getVar('datas')), true);
        $nama = upper_first(clear($this->request->getVar('nama')));
        $id = clear($this->request->getVar('id'));
        $db = \Config\Database::connect();
        $db->transStart();

        $nota = next_invoice("hutang");

        $tgl = time();

        foreach ($datas as $i) {
            $db->table('hutang')->insert([
                "no_nota" => $nota,
                "tgl" => $tgl,
                "jenis" => $i['jenis'],
                "barang" => $i['barang'],
                "barang_id" => $i['id'],
                "harga" => $i['harga'],
                "qty" => $i['qty'],
                "total" => $i['total'],
                "diskon" => $i['diskon'],
                "biaya" => $i['biaya'],
                "petugas" => user()['nama'],
                "lokasi" => user()['lokasi'],
                "nama" => $nama,
                "user_id" => $id
            ]);

            $barang = db('barang')->where('id', $i['id'])->get()->getRowArray();
            if (!$barang) {
                gagal_js("Id " . $i['barang'] . " not found");
            }
            $barang['qty'] -= (int)$i['qty'];
            db('barang')->where('id', $barang['id'])->update($barang);
        }


        $db->transComplete();

        return $db->transStatus()
            ? sukses_js("Sukses")
            : gagal_js("Gagal");
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

    public function cari_user()
    {
        $text = clear($this->request->getVar("text"));
        $roles = json_decode(json_encode($this->request->getVar("roles")), true);
        $data = db('user')->whereIn('role', $roles)->like("nama", $text, "both")->orderBy('nama', 'ASC')->limit(7)->get()->getResultArray();

        sukses_js("Ok", $data);
    }
    public function cari_barang()
    {
        $text = clear($this->request->getVar("text"));
        $jenis = json_decode(json_encode($this->request->getVar("jenis")), true);
        $data = db('barang')->whereIn('jenis', $jenis)->like("barang", $text, "both")->orderBy('barang', 'ASC')->limit(7)->get()->getResultArray();

        sukses_js("Ok", $data);
    }
    public function add_user()
    {
        $input = [
            "nama" => upper_first(clear($this->request->getVar("nama"))),
            "wa" => clear($this->request->getVar("wa")),
            "role" => "Member",
            "username" => random_string(4),
            "password" => password_hash(settings("password")['value'], PASSWORD_DEFAULT)
        ];

        db("user")->insert($input)
            ? sukses_js('Sukses')
            : gagal_js('Gagal');
    }
}

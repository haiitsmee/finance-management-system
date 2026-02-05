<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class exampleController extends Controller
{

    public function store(Request $request)
    {
        // Simpan data atau lakukan sesuatu dengan tanggal yang diterima
        $start = $request->input('start');
        $end = $request->input('end');

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'start' => $start,
            'end' => $end
        ]);
    }

    public function index($divisi)
    {
        // Logika untuk menampilkan data berdasarkan divisi, start, dan end
        return view('pages.admin-divisi.utang-piutang', [
            'divisi' => $divisi,
        ]);
    }
}

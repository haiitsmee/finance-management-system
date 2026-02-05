<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionCategoryRequest;
use App\Models\TransactionCategory;
use Cache;
use Illuminate\Http\Request;

class TransactionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $transactionCategories = Cache::remember('all_transaction_categories', '60', function () {
                return TransactionCategory::all();
            });

            $transactionCategory = null;

            return view('pages.superadmin.manajemen-kategori-transaksi', compact('transactionCategories', 'transactionCategory'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Kategori');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionCategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            $transactionCategory = TransactionCategory::create([
                'title' => $validated['judul_kategori'],
                'tags' => $validated['tipe_kategori']
            ]);

            if (!$transactionCategory) {
                notify()->error('Gagal membuat data kategori transaksi!', 'Kategori Transaksi');

                return redirect()->back();
            }

            Cache::forget('all_transaction_categories');

            notify()->success('Berhasil membuat data kategori transaksi.', 'Kategori Transaksi');
            return redirect()->back();
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Kategori');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $transactionCategories = Cache::remember('transaction_categories', 60, function () {
                return TransactionCategory::all();
            });

            $transactionCategory = TransactionCategory::find($id);


            return view('pages.superadmin.manajemen-kategori-transaksi', compact('transactionCategories', 'transactionCategory'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Kategori');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionCategoryRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $transactionCategory = TransactionCategory::find($id)->update([
                'title' => $validated['judul_kategori'],
                'tags' => $validated['tipe_kategori']
            ]);


            if (!$transactionCategory) {
                notify()->error('Gagal memperbaharui data kategori transaksi!', 'Kategori Transaksi');

                return redirect()->back();
            }


            Cache::forget('all_transaction_categories');

            notify()->success('Berhasil memperbaharui data kategori transaksi.', 'Kategori Transaksi');
            return redirect(route('manajemen-kategori.index'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Kategori');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $transactionCategory = TransactionCategory::find($id);

            if (!$transactionCategory) {
                notify()->error('Data admin tidak ditemukan!', 'Manajemen Admin');

                return redirect()->back();
            }

            $transactionCategory->delete();

            Cache::forget('all_transaction_categories');

            notify()->success('Berhasil menghapus data admin.', 'Manajemen Admin');
            return redirect()->back();
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Kategori');
            return redirect()->back();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessRequest;
use App\Models\Business;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $businesses = Cache::remember('all_businesses', '60', function () {
                return Business::with('users')->get();
            });

            return view('pages.superadmin.manajemen-divisi', compact('businesses'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Divisi');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $transactionDateFormatted = Carbon::now()
                ->isoFormat('dddd, D MMMM Y');

            return view('pages.superadmin.forms.manajemen-divisi.create', compact('transactionDateFormatted'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Divisi');
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BusinessRequest $request)
    {
        try {
            $validated = $request->validated();

            $business = Business::create([
                'name' => ucwords($validated['nama']),
                'slug' => Str::slug($validated['nama']),
                'responsible' => ucwords($validated['penanggung_jawab']),
                'contact_responsible' => $validated['kontak'],
                'employee_headcount' => $validated['jumlah_karyawan'],
                'description' => $validated['deskripsi']
            ]);

            if (!$business) {
                notify()->error('Divisi gagal ditambahkan!', 'Tambah Divisi');

                return redirect()->back();
            }

            Cache::forget('all_businesses');

            notify()->success('Berhasil menambahkan divisi.', 'Tambah Divisi');
            return redirect('/superadmin/manajemen-divisi');
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Divisi');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $business = Business::find($id);
            $transactionDateFormatted = Carbon::parse($business->created_at)
                ->isoFormat('dddd, D MMMM Y');

            return view('pages.superadmin.forms.manajemen-divisi.detail', compact('business', 'transactionDateFormatted'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Divisi');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $business = Business::find($id);


            return view('pages.superadmin.forms.manajemen-divisi.edit', compact('business'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Divisi');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BusinessRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $business = Business::find($id)->update(
                [
                    'name' => ucwords($validated['nama']),
                    'slug' => Str::slug($validated['nama']),
                    'responsible' => ucwords($validated['penanggung_jawab']),
                    'contact_responsible' => $validated['kontak'],
                    'employee_headcount' => $validated['jumlah_karyawan'],
                    'description' => $validated['deskripsi'],
                ]
            );

            if (!$business) {
                notify()->error('Gagal memperbaharui data divisi.', 'Edit Divisi');

                return redirect()->back();
            }

            Cache::forget('all_businesses');

            notify()->success('Berhasil memperbaharui data divisi.', 'Edit Divisi');
            return redirect('superadmin/manajemen-divisi');
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Divisi');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

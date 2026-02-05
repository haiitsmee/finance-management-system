<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionExpenseRequest;
use App\Models\Business;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Storage;
use Str;
use function Spatie\LaravelPdf\Support\pdf;

class TransactionExpenseMain extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $businesses = 'pusat';
        try {
            $search = $request->query('search');
            $tanggal = $request->query('tanggal');
            $start = $request->query('start');
            $end = $request->query('end');

            $business = Business::where('slug', $businesses)->firstOrFail();

            $query = Transaction::whereHas('transactionCategory', function ($q) {
                $q->where('tags', 'pengeluaran');
            });

            if (!empty($search)) {
                $query->where('product', 'like', "%{$search}%");
            }

            if (!empty($tanggal)) {
                $query->whereDate('transaction_date', $tanggal);
            }

            $transactionExpenses = $query
                ->orderBy('transaction_date', 'desc')
                ->where('business_id', $business->id)
                ->paginate(5)
                ->withQueryString();

            $transactionCategories = TransactionCategory::where('tags', 'pengeluaran')->get();

            return view('pages.superadmin.forms.catat-pengeluaran.catat-pengeluaran', compact('transactionExpenses', 'transactionCategories'));
        } catch (\Exception $ex) {
            dd($ex);

            notify()->error('Kesalahan pada server.', 'Catat Pengeluaran');
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
    public function store(TransactionExpenseRequest $request)
    {
        DB::beginTransaction();
        $businesses = 'pusat';
        try {
            $validated = $request->validated();


            $business = Business::where('slug', $businesses)->firstOrFail();
            $businessId = $business->id;

            $shortPath = $this->storeImageAndGetShortPath($request->file('gambar'), 'images/bukti/pengeluaran/');

            $transaction = Transaction::create([
                'business_id' => $businessId,
                'transaction_category_id' => $validated['transaction_category_id'],
                'product' => ucwords($validated['produk']),
                'price' => (int) str_replace(['Rp', '.', ' '], '', $validated['harga']),
                'product_quantity' => $validated['jumlah_unit'],
                'total' => (int) str_replace(['Rp', '.', ' '], '', $validated['total']),
                'transaction_date' => $validated['tanggal_transaksi'],
                'description' => $validated['deskripsi'],
                'status' => $validated['status'],
                'image' => $shortPath
            ]);

            if (!$transaction) {
                notify()->error('Gagal menambahkan data pengeluaran.', 'Catat Pengeluaran');

                return redirect()->back();
            }

            Cache::forget('all_transactions');

            notify()->success('Berhasil menambahkan data pengeluaran', 'Catat Pengeluaran');

            DB::commit();
            return redirect()->back();
        } catch (\Exception $ex) {
            DB::rollBack();
            notify()->error('Kesalahan pada server.', 'Catat Pengeluaran');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( string $id)
    {
        $businesses = 'pusat';
        try {
            $business = Business::where('slug', $businesses)->firstOrFail();
            $transactionExpense = Transaction::with('transactionCategory')
                ->where('id', $id)
                ->where('business_id', $business->id)
                ->first();

            $fileExists = $transactionExpense->image
                ? Storage::disk('public')->exists($transactionExpense->image)
                : false;

            $transactionDateFormatted = Carbon::parse($transactionExpense->transaction_date)
                ->isoFormat('dddd, D MMMM Y');


            return view('pages.admin.details.detail-pengeluaran', compact('transactionExpense', 'fileExists', 'transactionDateFormatted'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Catat Pengeluaran');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function storeImageAndGetShortPath($file, $folder = 'images/bukti/pengeluaran/')
    {
        $path = $file->store($folder, 'public');

        return $path;
    }

    public function printPdf( $id)
    {
        $businesses = 'pusat';
        try {
            $business = Business::where('slug', $businesses)->firstOrFail();
            $transactionIncome = Transaction::with('transactionCategory')
                ->where('id', $id)
                ->where('business_id', $business->id)
                ->first();

            $imageData = null;

            if ($transactionIncome->image && Storage::disk('public')->exists($transactionIncome->image)) {
                $path = Storage::disk('public')->path($transactionIncome->image);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $imageData = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }

            $transactionDateFormatted = Carbon::parse($transactionIncome->transaction_date)
                ->isoFormat('dddd, D MMMM Y');

            return pdf()
                ->view('pages.admin.pdf.bukti-pengeluaran', compact('transactionIncome', 'imageData', 'transactionDateFormatted'))
                ->name('bukti-pengeluaran-' . date('y-m-d', strtotime($transactionIncome->transaction_date)) . '.pdf');
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Catat Pengeluaran');
            return redirect()->back();
        }
    }
}

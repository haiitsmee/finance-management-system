<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Transaction;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Storage;
use Str;
use function Spatie\LaravelPdf\Support\pdf;

class UtangPiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public $businesses;

    public function index(Request $request, $businesses)
    {
        $debtQuery = Debt::query();

        // Sorting logic
        if ($request->sort_by === 'date') {
            $debtQuery->orderBy('date', 'asc');
        } elseif ($request->sort_by === 'status') {
            // Belum terbayar (is_paid = 0) tampil duluan
            $debtQuery->orderBy('is_paid', 'asc');
        } else {
            $debtQuery->latest(); // default
        }

        $receivableQuery = Transaction::query();

        // Sorting logic
        if ($request->sort_by === 'date') {
            $receivableQuery->orderBy('created_at', 'asc')->where('status', 'bon');
        } else {
            $receivableQuery->where('status', 'bon')->latest();
        }

        $debts = $debtQuery->whereHas('business', function ($q) use ($businesses) {
            $q->where('slug', $businesses);
        })->paginate(10);
        $receivables = $receivableQuery->paginate(10);


        return view('pages.admin.utang-piutang', [
            'utangData' => $debts,
            'piutangData' => $receivables,
            'business' => $businesses,
        ]);
    }

    public function retrieve()
    {
        // Logic to retrieve data based on the divisi
        // $data = Debt::where('divisi', $this->divisi)->get();

        $data = [
            ['id' => 1, 'date' => '22-09-2024', 'supplier' => 'Supplier A', 'category' => 'Pembelian barang', 'amount' => 100000, 'due_date' => '22-01-2025', 'is_paid' => false],
            ['id' => 2, 'date' => '22-09-2024', 'supplier' => 'Supplier B', 'category' => 'Pembelian barang', 'amount' => 200000, 'due_date' => '22-01-2025', 'is_paid' => true],
            ['id' => 3, 'date' => '22-09-2024', 'supplier' => 'Supplier C', 'category' => 'Pembelian barang', 'amount' => 300000, 'due_date' => '22-01-2025', 'is_paid' => false],
            ['id' => 4, 'date' => '22-09-2024', 'supplier' => 'Supplier D', 'category' => 'Pembelian barang', 'amount' => 400000, 'due_date' => '22-01-2025', 'is_paid' => true],
            ['id' => 5, 'date' => '22-09-2024', 'supplier' => 'Supplier E', 'category' => 'Pembelian barang', 'amount' => 500000, 'due_date' => '22-01-2025', 'is_paid' => false],
            ['id' => 6, 'date' => '22-09-2024', 'supplier' => 'Supplier F', 'category' => 'Pembelian barang', 'amount' => 600000, 'due_date' => '22-01-2025', 'is_paid' => true],
            ['id' => 7, 'date' => '22-09-2024', 'supplier' => 'Supplier G', 'category' => 'Pembelian barang', 'amount' => 700000, 'due_date' => '22-01-2025', 'is_paid' => false],
            ['id' => 8, 'date' => '22-09-2024', 'supplier' => 'Supplier H', 'category' => 'Pembelian barang', 'amount' => 800000, 'due_date' => '22-01-2025', 'is_paid' => true],
            ['id' => 9, 'date' => '22-09-2024', 'supplier' => 'Supplier I', 'category' => 'Pembelian barang', 'amount' => 900000, 'due_date' => '22-01-2025', 'is_paid' => false],
            ['id' => 10, 'date' => '22-09-2024', 'supplier' => 'Supplier J', 'category' => 'Pembelian barang', 'amount' => 1000000, 'due_date' => '22-01-2025', 'is_paid' => true]
        ];

        return $data;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $businesses)
    {
        $validated = $request->validate([
            'business_name' => 'required|string',
            'category' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'amount' => 'required|integer',
            'due_date' => 'required|date',
            'date' => 'required|date',
            'is_paid' => 'boolean',
            'description' => 'nullable|string',
            'document_path' => 'nullable|file|mimes:pdf,jpg,png,docx|max:2048',
        ]);


        try {
            if ($request->hasFile('document_path')) {
                $validated['document_path'] = $this->storeImageAndGetShortPath($request->file('document_path'), 'images/bukti/utang/');
            }

            $validated['business_id'] = Business::where('slug', $businesses)->value('id');

            $debt = Debt::create($validated);

            notify()->success('Data hutang berhasil ditambahkan.', 'Utang Piutang');
            return redirect()
                ->route('admin.utangpiutang.index', ['businesses' => $businesses])
                ->with('success', 'Data hutang berhasil ditambahkan.');
        } catch (Exception $e) {
            notify()->error('Gagal menambahkan data hutang.', 'Utang Piutang');

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($business, Debt $id)
    {
        return view('pages.admin.details.detail-utang', [
            'business' => $business,
            'utangData' => $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($business, Debt $id, Request $request, )
    {
        $debt = $id;
        $validated = $request->validate([
            'business_id' => 'required|integer',
            'category' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'amount' => 'required|integer',
            'due_date' => 'required|date',
            'date' => 'required|date',
            'is_paid' => 'boolean',
            'description' => 'nullable|string',
            'document_path' => 'nullable|file|mimes:pdf,jpg,png,docx|max:2048',
        ]);

        $debt->update($validated);

        return redirect()->route('admin.utangpiutang.index', ['businesses' => session('current_business')])
            ->with('success', 'Data berhasil diperbarui');
    }

    public function edit($businesses, Debt $id)
    {
        $debt = $id;
        return view('pages.admin.forms.utang.edit', compact('debt'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($businesses, Debt $id)
    {
        $id->delete();

        return redirect()
            ->route('admin.utangpiutang.index', $businesses)
            ->with('success', 'Data utang berhasil dihapus.');
    }

    public function new()
    {
        return view('pages.admin.forms.utang.');
    }

    public function create()
    {
        return view('pages.admin.forms.utang.create');
    }


    public function settleDebt(Request $request, $businesses, Debt $id)
    {
        $validated = $request->validate([
            'repayment_document' => 'required|file|mimes:pdf,jpg,png,docx|max:2048',
        ]);

        try {
            // Simpan file dokumen pelunasan
            if ($request->hasFile('repayment_document')) {
                $shortPath = $this->storeImageAndGetShortPath($request->file('repayment_document'), 'images/bukti/utang/pelunasan/');

                $id->repayment_document_path = $shortPath;
            }

            $id->is_paid = true;
            $id->save();

            notify()->success('Berhasil melakukan pelunasan', 'Utang Piutang');
            return redirect()->back();
        } catch (Exception $e) {
            notify()->error('Gagal melakukan pelunasan. Kesalahan server.', 'Utang Piutang');

            return redirect()
                ->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat melunasi hutang.']);
        }
    }

    public function printPdf($businesses, $id)
    {
        try {
            $business = Business::where('slug', $businesses)->firstOrFail();

            $debt = Debt::find($id);

            $imageDocument = null;
            $imageRepayment = null;

            if ($debt->document_path && Storage::disk('public')->exists($debt->document_path)) {
                $imageDocument = $this->getImage($debt->document_path);
            }

            if ($debt->repayment_document_path && Storage::disk('public')->exists($debt->repayment_document_path)) {
                $imageRepayment = $this->getImage($debt->repayment_document_path);
            }

            return pdf()
                ->view('pages.admin.pdf.bukti-utang-piutang', compact('business', 'debt', 'imageDocument', 'imageRepayment'))
                ->name('utang-piutang-' . date('y-m-d', strtotime($debt->date)) . '.pdf');
        } catch (Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Utang Piutang');
            return redirect()->back();
        }
    }

    public function getImage($imagePath)
    {
        $path = Storage::disk('public')->path($imagePath);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $imageData = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $imageData;
    }

    private function storeImageAndGetShortPath($file, $folder = 'images/bukti/pemasukan/')
    {
        $path = $file->store($folder, 'public');

        return $path;
    }
}

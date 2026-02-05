<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\DB;
use App\Models\Business;
use Carbon\Carbon;
use function Spatie\LaravelPdf\Support\pdf;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($businesses, Request $request)
    {
        $bulanMap = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12,
        ];
        $bulanMapInverse = array_flip($bulanMap);

        $business = Business::where('slug', $businesses)->firstOrFail();

        $monthName = $request->query('month');
        $date = $request->query('date');

        // default null
        $month = null;

        if (!empty($monthName) && isset($bulanMap[$monthName])) {
            $month = $bulanMap[$monthName];
        }

        if (empty($month) && !empty($date)) {
            try {
                $carbonDate = Carbon::parse($date);
                $month = $carbonDate->month; // 1–12
            } catch (Exception $e) {
                $month = null;
            }
        }

        $queryDetail = Transaction::query();
        $query = Transaction::select(
            DB::raw("DATE(transaction_date) as tanggal"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pemasukan' THEN total ELSE 0 END) as total_pemasukan"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pengeluaran' THEN total ELSE 0 END) as total_pengeluaran")
        );

        if (!empty($date)) {
            $transactionDetail = $queryDetail
                ->orderBy('transaction_date', 'desc')
                ->where('business_id', $business->id)
                ->whereDate('transaction_date', $date)
                ->paginate(5)
                ->withQueryString();
        }

        if (!empty($month)) {
            $query->whereMonth('transaction_date', $month);
        }

        $transactions = $query
            ->join('transaction_categories as tc', 'transactions.transaction_category_id', '=', 'tc.id')
            ->groupBy(DB::raw("DATE(transaction_date)"))
            ->orderBy('tanggal', 'asc')
            ->where('business_id', $business->id)
            ->whereYear('transaction_date', now()->year)
            ->get()
            ->map(function ($row) {
                return [
                    'date' => $row->tanggal,
                    'income' => (int) $row->total_pemasukan,
                    'outcome' => (int) $row->total_pengeluaran,
                    'revenue' => (int) $row->total_pemasukan - (int) $row->total_pengeluaran,
                ];
            });

        return view('pages.admin.laporan-keuangan', [
            'business' => $businesses,
            'data' => $transactions,
            'detail' => $transactionDetail ?? null,
            'month' => $month ? $bulanMapInverse[$month] : null, // kembalikan nama bulan
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($businesses, Request $request)
    {
        $bulanMap = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12,
        ];

        $business = Business::where('slug', $businesses)->firstOrFail();

        $monthName = $request->route('date');
        $month = $bulanMap[$monthName] ?? null;

        $query = Transaction::select(
            DB::raw("DATE(transaction_date) as tanggal"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pemasukan' THEN total ELSE 0 END) as total_pemasukan"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pengeluaran' THEN total ELSE 0 END) as total_pengeluaran")
        );

        if (!empty($month)) {
            $query->whereMonth('transaction_date', $month);
        }

        $transactions = $query
            ->join('transaction_categories as tc', 'transactions.transaction_category_id', '=', 'tc.id')
            ->groupBy(DB::raw("DATE(transaction_date)"))
            ->orderBy('tanggal', 'asc')
            ->where('business_id', $business->id)
            ->whereYear('transaction_date', now()->year)
            ->get()
            ->map(function ($row) {
                return [
                    'tanggal' => $row->tanggal,
                    'total_pemasukan' => (int) $row->total_pemasukan,
                    'total_pengeluaran' => (int) $row->total_pengeluaran,
                    'revenue' => (int) $row->total_pemasukan - (int) $row->total_pengeluaran,
                ];
            });

        $summaryQuery = Transaction::select(
            DB::raw("SUM(CASE WHEN tc.tags = 'pemasukan' THEN total ELSE 0 END) as total_pemasukan_bulanan"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pengeluaran' THEN total ELSE 0 END) as total_pengeluaran_bulanan")
        )
            ->join('transaction_categories as tc', 'transactions.transaction_category_id', '=', 'tc.id')
            ->where('business_id', $business->id)
            ->whereYear('transaction_date', now()->year);

        if (!empty($month)) {
            $summaryQuery->whereMonth('transaction_date', $month);
        }

        $summaryResult = $summaryQuery->first();

        $totalPemasukanBulanan = (int) $summaryResult->total_pemasukan_bulanan;
        $totalPengeluaranBulanan = (int) $summaryResult->total_pengeluaran_bulanan;
        $labaBersihBulanan = $totalPemasukanBulanan - $totalPengeluaranBulanan;
        $csr = $labaBersihBulanan > 0 ? $labaBersihBulanan * 0.025 : 0;

        $summary = [
            'total_pemasukan_bulanan' => $totalPemasukanBulanan,
            'total_pengeluaran_bulanan' => $totalPengeluaranBulanan,
            'laba_bersih_bulanan' => $labaBersihBulanan,
            'csr' => $csr
        ];

        return view('pages.admin.details.detail-report', [
            'business' => $businesses,
            'data' => $transactions,
            'summary' => $summary,
            'month' => $monthName
        ]);
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

    public function printPdf($businesses, $id, Request $request)
    {
        try {
            $bulanMap = [
                'Januari' => 1,
                'Februari' => 2,
                'Maret' => 3,
                'April' => 4,
                'Mei' => 5,
                'Juni' => 6,
                'Juli' => 7,
                'Agustus' => 8,
                'September' => 9,
                'Oktober' => 10,
                'November' => 11,
                'Desember' => 12,
            ];

            $business = Business::where('slug', $businesses)->firstOrFail();

            $monthName = $request->route('month');
            $month = $bulanMap[$monthName] ?? null;

            $imageData = null;

            $path = public_path('images/sekar-satria-icon.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $imageData = 'data:image/' . $type . ';base64,' . base64_encode($data);
            

            $query = Transaction::select(
                DB::raw("DATE(transaction_date) as tanggal"),
                DB::raw("SUM(CASE WHEN tc.tags = 'pemasukan' THEN total ELSE 0 END) as total_pemasukan"),
                DB::raw("SUM(CASE WHEN tc.tags = 'pengeluaran' THEN total ELSE 0 END) as total_pengeluaran")
            );

            if (!empty($month)) {
                $query->whereMonth('transaction_date', $month);
            }

            $transactions = $query
                ->join('transaction_categories as tc', 'transactions.transaction_category_id', '=', 'tc.id')
                ->groupBy(DB::raw("DATE(transaction_date)"))
                ->orderBy('tanggal', 'asc')
                ->where('business_id', $business->id)
                ->whereYear('transaction_date', now()->year)
                ->get()
                ->map(function ($row) {
                    return [
                        'tanggal' => $row->tanggal,
                        'total_pemasukan' => (int) $row->total_pemasukan,
                        'total_pengeluaran' => (int) $row->total_pengeluaran,
                        'revenue' => (int) $row->total_pemasukan - (int) $row->total_pengeluaran,
                    ];
                });

            $summaryQuery = Transaction::select(
                DB::raw("SUM(CASE WHEN tc.tags = 'pemasukan' THEN total ELSE 0 END) as total_pemasukan_bulanan"),
                DB::raw("SUM(CASE WHEN tc.tags = 'pengeluaran' THEN total ELSE 0 END) as total_pengeluaran_bulanan")
            )
                ->join('transaction_categories as tc', 'transactions.transaction_category_id', '=', 'tc.id')
                ->where('business_id', $business->id)
                ->whereYear('transaction_date', now()->year);

            if (!empty($month)) {
                $summaryQuery->whereMonth('transaction_date', $month);
            }

            $summaryResult = $summaryQuery->first();

            $totalPemasukanBulanan = (int) $summaryResult->total_pemasukan_bulanan;
            $totalPengeluaranBulanan = (int) $summaryResult->total_pengeluaran_bulanan;
            $labaBersihBulanan = $totalPemasukanBulanan - $totalPengeluaranBulanan;
            $csr = $labaBersihBulanan > 0 ? $labaBersihBulanan * 0.025 : 0;

            $summary = [
                'total_pemasukan_bulanan' => $totalPemasukanBulanan,
                'total_pengeluaran_bulanan' => $totalPengeluaranBulanan,
                'laba_bersih_bulanan' => $labaBersihBulanan,
                'csr' => $csr
            ];

            $letterhead = $business->getLetterheadData();
            return pdf()->view('pages.admin.pdf.laporan-keuangan', [
                'business' => $businesses,
                'data' => $transactions,
                'summary' => $summary,  
                'month' => $monthName,
                'letterhead' => $letterhead,
                'imageData' => $imageData,
            ])->name('laporan-keuangan-' . date('y-m-d', strtotime(now())) . '.pdf');
        } catch (Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Laporan Keuangan');
            return redirect()->back();
        }
    }
}

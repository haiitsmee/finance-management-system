<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use function Spatie\LaravelPdf\Support\pdf;

class ReportAllController extends Controller
{
    public function index(Request $request)
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
            $bulanMapInverse = array_flip($bulanMap);

            $monthName = $request->query('month');
            $date = $request->query('id');

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

            $query = Business::select(
                'businesses.id as business_id',
                'businesses.name as business_name',
                DB::raw("COALESCE(SUM(CASE WHEN tc.tags = 'pemasukan' THEN transactions.total ELSE 0 END), 0) as total_pemasukan"),
                DB::raw("COALESCE(SUM(CASE WHEN tc.tags = 'pengeluaran' THEN transactions.total ELSE 0 END), 0) as total_pengeluaran")
            )
                ->leftJoin('transactions', function ($join) use ($month) {
                    $join->on('transactions.business_id', '=', 'businesses.id')
                        ->whereYear('transactions.transaction_date', now()->year);

                    if (!empty($month)) {
                        $join->whereMonth('transactions.transaction_date', $month);
                    }
                })
                ->leftJoin('transaction_categories as tc', 'transactions.transaction_category_id', '=', 'tc.id')
                ->groupBy('businesses.id', 'businesses.name')
                ->orderBy('businesses.name', 'asc');


            $transactions = $query->get()
                ->map(function ($row) {
                    return [
                        'id' => $row->business_id,
                        'name' => $row->business_name, // nama divisi
                        'income' => (int) $row->total_pemasukan,
                        'outcome' => (int) $row->total_pengeluaran,
                        'revenue' => (int) $row->total_pemasukan - (int) $row->total_pengeluaran,
                    ];
                })
                ->values();


            return view('pages.superadmin.laporan-keuangan', [
                'data' => $transactions,
                'month' => $month ? $bulanMapInverse[$month] : null, // kembalikan nama bulan
            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }
    }

    public function show(Request $request)
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

        $monthName = $request->query('month');

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

        $query = Business::select(
            'businesses.id as business_id',
            'businesses.name as business_name',
            DB::raw("COALESCE(SUM(CASE WHEN tc.tags = 'pemasukan' THEN transactions.total ELSE 0 END), 0) as total_pemasukan"),
            DB::raw("COALESCE(SUM(CASE WHEN tc.tags = 'pengeluaran' THEN transactions.total ELSE 0 END), 0) as total_pengeluaran")
        )
            ->leftJoin('transactions', function ($join) use ($month) {
                $join->on('transactions.business_id', '=', 'businesses.id')
                    ->whereYear('transactions.transaction_date', now()->year);

                if (!empty($month)) {
                    $join->whereMonth('transactions.transaction_date', $month);
                }
            })
            ->leftJoin('transaction_categories as tc', 'transactions.transaction_category_id', '=', 'tc.id')
            ->groupBy('businesses.id', 'businesses.name')
            ->orderBy('businesses.name', 'asc');


        $transactions = $query->get()
            ->map(function ($row) {
                return [
                    'id' => $row->business_id,
                    'name' => $row->business_name, // nama divisi
                    'income' => (int) $row->total_pemasukan,
                    'outcome' => (int) $row->total_pengeluaran,
                    'revenue' => (int) $row->total_pemasukan - (int) $row->total_pengeluaran,
                ];
            })
            ->values();

        $summary = [
            'total_income' => $transactions->sum('income'),
            'total_outcome' => $transactions->sum('outcome'),
            'total_revenue' => $transactions->sum('revenue'),
        ];

        return view('pages.superadmin.forms.laporan-keuangan.detail', [
            'data' => $transactions,
            'summary' => $summary,
            'month' => $month ? $bulanMapInverse[$month] : null, // kembalikan nama bulan
        ]);
    }

    public function printPdf(Request $request)
    {
        $business = Business::find(1); // ambil data divisi pusat

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

        $monthName = $request->query('month');

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

        $query = Business::select(
            'businesses.id as business_id',
            'businesses.name as business_name',
            DB::raw("COALESCE(SUM(CASE WHEN tc.tags = 'pemasukan' THEN transactions.total ELSE 0 END), 0) as total_pemasukan"),
            DB::raw("COALESCE(SUM(CASE WHEN tc.tags = 'pengeluaran' THEN transactions.total ELSE 0 END), 0) as total_pengeluaran")
        )
            ->leftJoin('transactions', function ($join) use ($month) {
                $join->on('transactions.business_id', '=', 'businesses.id')
                    ->whereYear('transactions.transaction_date', now()->year);

                if (!empty($month)) {
                    $join->whereMonth('transactions.transaction_date', $month);
                }
            })
            ->leftJoin('transaction_categories as tc', 'transactions.transaction_category_id', '=', 'tc.id')
            ->groupBy('businesses.id', 'businesses.name')
            ->orderBy('businesses.name', 'asc');


        $transactions = $query->get()
            ->map(function ($row) {
                return [
                    'id' => $row->business_id,
                    'name' => $row->business_name, // nama divisi
                    'income' => (int) $row->total_pemasukan,
                    'outcome' => (int) $row->total_pengeluaran,
                    'revenue' => (int) $row->total_pemasukan - (int) $row->total_pengeluaran,
                ];
            })
            ->values();

        $summary = [
            'total_income' => $transactions->sum('income'),
            'total_outcome' => $transactions->sum('outcome'),
            'total_revenue' => $transactions->sum('revenue'),
        ];

        $letterhead = $business->getLetterheadData();

        return pdf()->
            view('pages.superadmin.pdf.laporan-keuangan', [
            'data' => $transactions,
            'summary' => $summary,
            'month' => $month ? $bulanMapInverse[$month] : null, // kembalikan nama bulan
            'letterhead' => $letterhead,
        ])
        ->name('laporan-keuangan-all-' . now() . '.pdf');;
    }
}

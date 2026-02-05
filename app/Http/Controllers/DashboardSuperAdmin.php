<?php
namespace App\Http\Controllers;
use App\Models\CSRSetting;
use App\View\Components\chart;
use Illuminate\Http\Request;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Str;
use App\Models\Transaction;
use App\Models\Business;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\DB;
use App\Models\CSRDistribution;
use Carbon\Carbon;


class DashboardSuperAdmin extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start') ?? null;
        $end = $request->input('end') ?? null;
        $distributionData = CSRDistribution::orderBy('date', 'desc')
            ->get()
            ->map(function ($distribution) {
                return [
                    'id' => $distribution->id,
                    'activity' => $distribution->activity,
                    'purpose' => $distribution->purpose,
                    'date' => Carbon::parse($distribution->date)->format('d/m/Y'),
                    'amount' => 'Rp ' . number_format($distribution->amount, 0, ',', '.')
                ];
            });

        return view('pages.superadmin.dashboard', [
            'start' => $start,
            'end' => $end,
            'summaryData' => $this->getSummaryData(),
            'lineChartData' => $this->lineChartData(),
            'trendData' => $this->getTrendData(),
            'businessSumary' => $this->incomeOutcomePerBusiness($start, $end),
            'profitData' => $this->getProfit(),
            'distributionData' => $distributionData,
            // 'expenseData'  => $this->expenseData($start, $end),
            // 'incomeData'   => $this->incomeData($start, $end),
        ]);
    }

    public function getSummaryData()
    {
        $query = Transaction::query();

        $totalIncome = (clone $query)->whereHas('transactionCategory', fn($q) => $q->where('tags', 'pemasukan'))->sum('total');
        $totalOutcome = (clone $query)->whereHas('transactionCategory', fn($q) => $q->where('tags', 'pengeluaran'))->sum('total');
        $totalRevenue = $totalIncome - $totalOutcome;

        $percentageCSR = CSRSetting::select('percentage')->latest()->value('percentage');

        return [
            [
                "time" => 'Bulan',
                "value1" => $totalIncome,
                "value2" => $totalOutcome,
                "value3" => $totalRevenue,
                "value4" => $percentageCSR,
            ]
        ];
    }


    public function handleRange(Request $request)
    {
        $start = $request['start'];
        $end = $request['end'];


        if ($end < $start) {
            return back()->withErrors(['date' => 'Tanggal akhir harus >= tanggal awal']);
        }

        return redirect()->route('superadmin.dashboard', [
            'start' => $start,
            'end' => $end
        ]);
    }

    public function lineChartData()
    {
        $query = Transaction::select(
            DB::raw("MONTH(transaction_date) as month"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pemasukan' THEN total ELSE 0 END) as income"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pengeluaran' THEN total ELSE 0 END) as outcome")
        )
            ->join('transaction_categories as tc', 'transactions.transaction_category_id', '=', 'tc.id');

        $results = $query->groupBy(DB::raw('MONTH(transaction_date)'))
            ->orderBy(DB::raw('MONTH(transaction_date)'))
            ->get();

        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $incomeData = array_fill(1, 12, 0);
        $outcomeData = array_fill(1, 12, 0);

        foreach ($results as $row) {
            $incomeData[$row->month] = (int) $row->income;
            $outcomeData[$row->month] = (int) $row->outcome;
        }

        return [
            'labels' => array_values($months),
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => array_values($incomeData),
                    'backgroundColor' => 'rgba(137, 121, 255, 0.2)',
                    'borderColor' => 'rgba(137, 121, 255, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => array_values($outcomeData),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'fill' => true,
                ],
            ],
        ];
    }

    public function incomeOutcomePerBusiness($start = null, $end = null)
    {
        $query = Business::select(
            'businesses.id',
            'businesses.name',
            DB::raw("SUM(CASE WHEN tc.tags = 'pemasukan' THEN t.total ELSE 0 END) as total_pemasukan"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pengeluaran' THEN t.total ELSE 0 END) as total_pengeluaran")
        )
            ->leftJoin('transactions as t', 'businesses.id', '=', 't.business_id')
            ->leftJoin('transaction_categories as tc', 't.transaction_category_id', '=', 'tc.id');


        if ($start && $end) {
            $query->whereBetween('t.transaction_date', [$start, $end]);
        }

        $results = $query
            ->groupBy('businesses.id', 'businesses.name')
            ->orderBy('businesses.name')
            ->get();

        $labels = $results->pluck('name')->toArray();
        $pemasukan = $results->pluck('total_pemasukan')->toArray();
        $pengeluaran = $results->pluck('total_pengeluaran')->toArray();

        return [
            'labels' => array_values($labels),
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => array_values($pemasukan),
                    'backgroundColor' => 'rgba(137, 121, 255, 0.2)',
                    'borderColor' => 'rgba(137, 121, 255, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => array_values($pengeluaran),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'fill' => true,
                ],
            ],
        ];

    }

    public function getProfit()
    {
        $businesses = Business::all();

        $reportData = $businesses->map(function ($business) {
            $totalIncome = Transaction::where('business_id', $business->id)
                ->whereHas('transactionCategory', fn($q) => $q->where('tags', 'pemasukan'))
                ->sum('total');

            $totalOutcome = Transaction::where('business_id', $business->id)
                ->whereHas('transactionCategory', fn($q) => $q->where('tags', 'pengeluaran'))
                ->sum('total');

            $netProfit = $totalIncome - $totalOutcome;

            return [
                'business' => $business->slug,
                'name' => $business->name,
                'income' => $totalIncome,
                'outcome' => $totalOutcome,
                'revenue' => $netProfit,
            ];
        });

        return $reportData;
    }

    private function generateColors($count)
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $r = rand(0, 255);
            $g = rand(0, 255);
            $b = rand(0, 255);

            $colors[] = "rgba($r, $g, $b, 0.2)";
        }
        return $colors;
    }

    public function getTrendData()
    {
        $query = Transaction::select(
            DB::raw("MONTH(transaction_date) as month"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pemasukan' THEN total ELSE 0 END) as income"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pengeluaran' THEN total ELSE 0 END) as outcome")
        )
            ->join("transaction_categories as tc", "transactions.transaction_category_id", "=", "tc.id");

        $results = $query->groupBy(DB::raw("MONTH(transaction_date)"))
            ->orderBy(DB::raw("MONTH(transaction_date)"))
            ->get();

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $trendData = [];
        foreach ($results as $row) {
            $income = (int) $row->income;
            $outcome = (int) $row->outcome * -1;
            $revenue = $income + $outcome;

            $lastRevenue = end($trendData)['revenue'] ?? null;
            $trend = ($lastRevenue && $lastRevenue != 0)
                ? (($revenue - $lastRevenue) / abs($lastRevenue)) * 100
                : 0;

            $trendData[] = [
                "month" => $months[$row->month],
                "income" => $income,
                "outcome" => $outcome,
                "revenue" => $revenue,
                "trend" => round($trend, 2),
            ];
        }

        return $trendData;
    }

    public function expenseData($business, $start, $end)
    {
        $businesses = Business::where('slug', $business)->firstOrFail();


        $query = Transaction::with('transactionCategory')
            ->where('business_id', $businesses->id)
            ->whereHas('transactionCategory', function ($q) {
                $q->where('tags', 'pengeluaran'); // filter pengeluaran
            });

        // filter range tanggal jika ada
        if ($start && $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        }

        // ambil transaksi dan kelompokkan per kategori
        $results = $query->get()->groupBy(fn($t) => $t->transactionCategory->title);

        $expenseData = [];
        $grandTotal = $results->flatten()->sum('total');

        foreach ($results as $category => $transactions) {
            $sum = $transactions->sum('total');
            $expenseData[] = [
                "id" => count($expenseData) + 1,
                "category" => $category,
                "total" => $sum,
                "persentage" => $grandTotal > 0 ? round(($sum / $grandTotal) * 100, 2) : 0,
                "start" => $start,
                "end" => $end,
            ];
        }

        return $expenseData;

    }
    public function incomeData($business, $start, $end)
    {
        $businesses = Business::where('slug', $business)->firstOrFail();


        $query = Transaction::with('transactionCategory')
            ->where('business_id', $businesses->id)
            ->whereHas('transactionCategory', function ($q) {
                $q->where('tags', 'pemasukan'); // filter pengeluaran
            });

        // filter range tanggal jika ada
        if ($start && $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        }

        // ambil transaksi dan kelompokkan per kategori
        $results = $query->get()->groupBy(fn($t) => $t->transactionCategory->title);

        $incomeData = [];
        $grandTotal = $results->flatten()->sum('total');

        foreach ($results as $category => $transactions) {
            $sum = $transactions->sum('total');
            $incomeData[] = [
                "id" => count($incomeData) + 1,
                "category" => $category,
                "total" => $sum,
                "persentage" => $grandTotal > 0 ? round(($sum / $grandTotal) * 100, 2) : 0,
                "start" => $start,
                "end" => $end,
            ];
        }

        return $incomeData;

    }
}
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


class DashboardBusinessController extends Controller
{

    public $businesses;

    public function index($businesses, Request $request)
    {
        $start = $request->input('start') ?? null;
        $end = $request->input('end') ?? null;



        return view('pages.admin.dashboard', [
            'businesses' => $businesses,
            'start' => $start,
            'end' => $end,
            'summaryData' => $this->getSummaryData($businesses),
            'lineChartData' => $this->lineChartData($businesses),
            'trendData' => $this->getTrendData($businesses),
            'expenseChart' => $this->expenseChart($businesses, $start, $end),
            'incomeChart' => $this->incomeChart($businesses, $start, $end),
            'expenseData' => $this->expenseData($businesses, $start, $end),
            'incomeData' => $this->incomeData($businesses, $start, $end),
        ]);
    }

    public function getSummaryData($businessSlug)
    {
        $business = Business::where('slug', $businessSlug)->firstOrFail();

        $totalIncome = Transaction::where('business_id', $business->id)
            ->whereHas('transactionCategory', fn($q) => $q->where('tags', 'pemasukan'))
            ->sum('total');

        $totalOutcome = Transaction::where('business_id', $business->id)
            ->whereHas('transactionCategory', fn($q) => $q->where('tags', 'pengeluaran'))
            ->sum('total');

        $percentageCSR = CSRSetting::select('percentage')->latest()->value('percentage');

        $totalRevenue = $totalIncome - $totalOutcome;

        $summaryData = [
            [
                "time" => 'Bulan',
                "value1" => $totalIncome,
                "value2" => $totalOutcome,
                "value3" => $totalRevenue,
                "value4" => $percentageCSR,
            ]
        ];

        return $summaryData;
    }


    public function handleRange($businesses, Request $request)
    {
        $start = $request['start'];
        $end = $request['end'];


        if ($end < $start) {
            return back()->withErrors(['date' => 'Tanggal akhir harus >= tanggal awal']);
        }

        return redirect()->route('admin.dashboard', [
            'businesses' => $businesses,
            'start' => $start,
            'end' => $end
        ]);
    }

    public function lineChartData($businesses)
    {
        $business = Business::where('slug', $businesses)->firstOrFail();
        $businessId = $business->id;

        $results = Transaction::select(
            DB::raw("MONTH(transaction_date) as month"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pemasukan' THEN total ELSE 0 END) as income"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pengeluaran' THEN total ELSE 0 END) as outcome")
        )
            ->join('transaction_categories as tc', 'transactions.transaction_category_id', '=', 'tc.id')
            ->where('transactions.business_id', $businessId)
            ->groupBy(DB::raw('MONTH(transaction_date)'))
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

        $chartData = [
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

        return $chartData;
    }

    public function expenseChart($business, $start = null, $end = null)
    {
        $businesses = Business::where('slug', $business)->firstOrFail();

        $query = Transaction::with('transactionCategory')
            ->where('business_id', $businesses->id)
            ->whereHas('transactionCategory', function ($q) {
                $q->where('tags', 'pengeluaran');
            });

        if ($start && $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        }

        $results = $query->get()->groupBy(fn($t) => $t->transactionCategory->title);

        $labels = [];
        $data = [];

        foreach ($results as $category => $transactions) {
            $labels[] = $category;
            $data[] = $transactions->sum('total');
        }

        $colors = $this->generateColors(count($labels));

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Pengeluaran',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => str_replace("0.2", "1", $colors),
                    'borderWidth' => 1,
                ]
            ]
        ];
    }
    public function incomeChart($business, $start = null, $end = null)
    {
        $businesses = Business::where('slug', $business)->firstOrFail();

        $query = Transaction::with('transactionCategory')
            ->where('business_id', $businesses->id)
            ->whereHas('transactionCategory', function ($q) {
                $q->where('tags', 'pemasukan');
            });

        if ($start && $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        }

        $results = $query->get()->groupBy(fn($t) => $t->transactionCategory->title);

        $labels = [];
        $data = [];

        foreach ($results as $category => $transactions) {
            $labels[] = $category;
            $data[] = $transactions->sum('total');
        }

        $colors = $this->generateColors(count($labels));

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => str_replace("0.2", "1", $colors),
                    'borderWidth' => 1,
                ]
            ]
        ];
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

    public function getTrendData($business)
    {
        $businesses = Business::where('slug', $business)->firstOrFail();

        $results = Transaction::select(
            DB::raw("MONTH(transaction_date) as month"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pemasukan' THEN total ELSE 0 END) as income"),
            DB::raw("SUM(CASE WHEN tc.tags = 'pengeluaran' THEN total ELSE 0 END) as outcome")
        )
            ->join("transaction_categories as tc", "transactions.transaction_category_id", "=", "tc.id")
            ->where("transactions.business_id", $businesses->id)
            ->groupBy(DB::raw("MONTH(transaction_date)"))
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
            if ($lastRevenue && $lastRevenue != 0) {
                $trend = (($revenue - $lastRevenue) / abs($lastRevenue)) * 100;
            } else {
                $trend = 0;
            }

            $trendData[] = [
                "month" => $months[$row->month],
                "income" => $income,
                "outcome" => $outcome,
                "revenue" => $revenue,
                "trend" => round($trend, 2),
            ];
        }

        echo "<script>console.log(" . json_encode($trendData) . ");</script>";


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


        if ($start && $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        }


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


        if ($start && $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        }


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
<?php

namespace App\Http\Controllers;

use App\Models\CSRSetting;
use App\Models\CSRIncome;
use App\Models\CSRWithdrawal;
use App\Models\CSRDistribution;
use App\Models\Business;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use function Spatie\LaravelPdf\Support\pdf;

class CSRController extends Controller
{
    /**
     * Menampilkan dashboard CSR
     */
    public function index()
    {
        // Pastikan CSR terhitung sebelum menampilkan data
        $this->ensureCSRCalculated();

        $csrSetting = CSRSetting::firstOrCreate([], ['percentage' => 0]);

        // Hitung available balance yang benar: total withdrawal - total distribusi
        $totalWithdrawal = CSRWithdrawal::sum('amount');
        $totalDistribution = CSRDistribution::sum('amount');
        $availableBalance = $totalWithdrawal - $totalDistribution;

        // Data income yang belum ditarik (hanya untuk informasi)
        $incomeData = CSRIncome::with('business')
            ->where('is_withdrawn', false)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($income) {
                if ($income->type === 'automatic') {
                    $source = ($income->business->name ?? 'Unknown Business') . ' (Profit)';
                } else {
                    $source = $income->source . ' (Manual)';
                }

                return [
                    'source' => $source,
                    'amount' => 'Rp ' . number_format($income->amount, 0, ',', '.'),
                    'status' => 'Belum ditarik',
                    'raw_amount' => $income->amount
                ];
            });

        $withdrawalHistory = CSRWithdrawal::orderBy('date', 'desc')
            ->get()
            ->map(function ($withdrawal) {
                return [
                    'date' => Carbon::parse($withdrawal->date)->format('d/m/Y H:i:s'),
                    'amount' => 'Rp ' . number_format($withdrawal->amount, 0, ',', '.')
                ];
            });

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

        // Total income dari data yang ditampilkan (belum ditarik)
        $totalIncome = $incomeData->sum('raw_amount');

        return view('pages.superadmin.csr', [
            'percentage' => $csrSetting->percentage,
            'incomeData' => $incomeData,
            'availableBalance' => 'Rp ' . number_format($availableBalance, 0, ',', '.'),
            'totalIncome' => 'Rp ' . number_format($totalIncome, 0, ',', '.'),
            'totalWithdrawal' => 'Rp ' . number_format($totalWithdrawal, 0, ',', '.'),
            'totalDistribution' => 'Rp ' . number_format($totalDistribution, 0, ',', '.'),
            'withdrawalHistory' => $withdrawalHistory,
            'distributionData' => $distributionData
        ]);
    }

    /**
     * Memastikan CSR sudah dihitung berdasarkan profit sejak last withdrawal
     */
    private function ensureCSRCalculated()
    {
        $csrSetting = CSRSetting::first();
        if (!$csrSetting)
            return;

        // Cek kapan terakhir kali CSR dihitung (per jam, bukan per hari)
        $lastCSRCalculation = CSRIncome::where('type', 'automatic')
            ->orderBy('created_at', 'desc')
            ->value('created_at');

        // Jika belum pernah dihitung atau sudah lewat 1 jam sejak terakhir hitung
        if (!$lastCSRCalculation || Carbon::parse($lastCSRCalculation)->diffInHours(now()) >= 1) {
            $this->calculateCSRSinceLastWithdrawal($csrSetting->percentage);
        }
    }

    /**
     * Hitung CSR berdasarkan profit sejak last withdrawal
     */
    private function calculateCSRSinceLastWithdrawal($percentage)
    {
        // Dapatkan tanggal dan waktu terakhir withdrawal
        $lastWithdrawal = CSRWithdrawal::latest('date')->first();
        $startDate = $lastWithdrawal ? Carbon::parse($lastWithdrawal->date)->addSecond() : null;
        $endDate = Carbon::now(); // Sampai detik ini

        // Hapus CSR income automatic yang belum ditarik sejak last withdrawal
        $query = CSRIncome::where('type', 'automatic')->where('is_withdrawn', false);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        $query->delete();

        // Preload transaction categories untuk efisiensi
        $categories = TransactionCategory::all();
        $incomeCategoryIds = $categories->filter(function ($category) {
            return str_contains(strtolower($category->tags), 'pemasukan');
        })->pluck('id');

        $expenseCategoryIds = $categories->filter(function ($category) {
            return str_contains(strtolower($category->tags), 'pengeluaran');
        })->pluck('id');

        // Hitung profit per business dengan query optimized
        $businessProfits = Transaction::select(
            'business_id',
            DB::raw('SUM(CASE WHEN transaction_category_id IN (' . implode(',', $incomeCategoryIds->toArray()) . ') THEN total ELSE 0 END) as total_income'),
            DB::raw('SUM(CASE WHEN transaction_category_id IN (' . implode(',', $expenseCategoryIds->toArray()) . ') THEN total ELSE 0 END) as total_expense')
        )
            ->where('created_at', '<=', $endDate);

        if ($startDate) {
            $businessProfits->where('created_at', '>=', $startDate);
        }

        $businessProfits = $businessProfits->groupBy('business_id')->get();

        foreach ($businessProfits as $profitData) {
            $profit = $profitData->total_income - $profitData->total_expense;
            $csrAmount = $profit * ($percentage / 100);

            if ($csrAmount > 0) {
                CSRIncome::create([
                    'type' => 'automatic',
                    'business_id' => $profitData->business_id,
                    'amount' => $csrAmount,
                    'date' => now(),
                    'is_withdrawn' => false,
                    'description' => 'CSR otomatis dari profit sejak ' .
                        ($startDate ? $startDate->format('d/m/Y H:i:s') : 'awal') . ' hingga ' . $endDate->format('d/m/Y H:i:s')
                ]);
            }
        }
    }

    /**
     * Hitung CSR berdasarkan profit sampai detik ini
     */
    private function calculateCSRUpToNow($percentage)
    {
        // Dapatkan tanggal terakhir withdrawal
        $lastWithdrawalDate = CSRWithdrawal::latest('date')->value('date');
        $startDate = $lastWithdrawalDate ? Carbon::parse($lastWithdrawalDate)->addSecond() : null;
        $endDate = Carbon::now(); // Sampai detik ini

        // Hapus CSR income automatic yang belum ditarik sejak last withdrawal
        $query = CSRIncome::where('type', 'automatic')->where('is_withdrawn', false);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        $query->delete();

        // Preload transaction categories untuk efisiensi
        $categories = TransactionCategory::all();
        $incomeCategoryIds = $categories->filter(function ($category) {
            return str_contains(strtolower($category->tags), 'pemasukan');
        })->pluck('id');

        $expenseCategoryIds = $categories->filter(function ($category) {
            return str_contains(strtolower($category->tags), 'pengeluaran');
        })->pluck('id');

        // Hitung profit per business dengan query optimized
        $businessProfits = Transaction::select(
            'business_id',
            DB::raw('SUM(CASE WHEN transaction_category_id IN (' . implode(',', $incomeCategoryIds->toArray()) . ') THEN total ELSE 0 END) as total_income'),
            DB::raw('SUM(CASE WHEN transaction_category_id IN (' . implode(',', $expenseCategoryIds->toArray()) . ') THEN total ELSE 0 END) as total_expense')
        )
            ->where('created_at', '<=', $endDate);

        if ($startDate) {
            $businessProfits->where('created_at', '>=', $startDate);
        }

        $businessProfits = $businessProfits->groupBy('business_id')->get();

        foreach ($businessProfits as $profitData) {
            $profit = $profitData->total_income - $profitData->total_expense;
            $csrAmount = $profit * ($percentage / 100);

            if ($csrAmount > 0) {
                CSRIncome::create([
                    'type' => 'automatic',
                    'business_id' => $profitData->business_id,
                    'amount' => $csrAmount,
                    'date' => now(),
                    'is_withdrawn' => false,
                    'description' => 'CSR otomatis dari profit sejak ' .
                        ($startDate ? $startDate->format('d/m/Y H:i:s') : 'awal') . ' hingga ' . $endDate->format('d/m/Y H:i:s')
                ]);
            }
        }
    }

    /**
     * Update persentase CSR
     */
    public function updatePercentage(Request $request)
    {
        $request->validate([
            'percentage' => 'required|numeric|min:0|max:100'
        ]);

        $csrSetting = CSRSetting::firstOrCreate([], ['percentage' => 0]);
        $oldPercentage = $csrSetting->percentage;

        $csrSetting->update(['percentage' => $request->percentage]);

        // Jika percentage berubah, hitung ulang CSR dari profit sejak last withdrawal
        if ($oldPercentage != $request->percentage) {
            $this->calculateCSRSinceLastWithdrawal($request->percentage);
        }

        return redirect()->back()->with('success', 'Persentase CSR berhasil diperbarui');
    }

    /**
     * Menambah pemasukan CSR manual dari pihak eksternal
     */
    public function addManualIncome(Request $request)
    {
        $request->validate([
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'description' => 'nullable|string'
        ]);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('csr-income-documents', 'public');
        }

        CSRIncome::create([
            'type' => 'manual',
            'source' => $request->source,
            'amount' => $request->amount,
            'date' => $request->date,
            'document_path' => $documentPath,
            'is_withdrawn' => false,
            'description' => $request->description
        ]);

        return redirect()->route('superadmin.csr.index')->with('success', 'Pemasukan CSR manual berhasil ditambahkan');
    }

    /**
     * Melakukan penarikan semua dana CSR yang belum ditarik
     */
    public function withdrawAll(Request $request)
    {
        try {
            DB::transaction(function () {
                // Hitung CSR sampai detik ini sebelum penarikan
                $csrSetting = CSRSetting::first();
                if ($csrSetting) {
                    $this->calculateCSRUpToNow($csrSetting->percentage);
                }

                $totalAmount = CSRIncome::notWithdrawn()->sum('amount');

                if ($totalAmount <= 0) {
                    throw new \Exception('Tidak ada dana CSR yang dapat ditarik');
                }

                $withdrawal = CSRWithdrawal::create([
                    'amount' => $totalAmount,
                    'date' => now()
                ]);

                CSRIncome::notWithdrawn()->update(['is_withdrawn' => true]);
            });

            return redirect()->back()->with('success', 'Semua dana CSR berhasil ditarik');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['withdrawal' => $e->getMessage()]);
        }
    }

    /**
     * Menambah penyaluran CSR
     */
    public function addDistribution(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'description' => 'nullable|string'
        ]);

        $totalWithdrawal = CSRWithdrawal::sum('amount');
        $totalDistribution = CSRDistribution::sum('amount');
        $availableBalance = $totalWithdrawal - $totalDistribution;

        if ($availableBalance <= 0) {
            return redirect()->back()->withErrors([
                'amount' => 'Tidak ada saldo CSR yang tersedia untuk distribusi'
            ]);
        }

        if ($request->amount > $availableBalance) {
            return redirect()->back()->withErrors([
                'amount' => 'Saldo CSR tidak mencukupi. Saldo tersedia: Rp ' . number_format($availableBalance, 0, ',', '.')
            ]);
        }

        $documentPath = $request->file('document')->store('csr-distribution-documents', 'public');

        CSRDistribution::create([
            'activity' => $request->activity,
            'purpose' => $request->purpose,
            'amount' => $request->amount,
            'date' => $request->date,
            'document_path' => $documentPath,
            'description' => $request->description
        ]);

        return redirect()->route('superadmin.csr.index')->with('success', 'Penyaluran CSR berhasil ditambahkan');
    }

    /**
     * Cron job untuk generate CSR otomatis secara berkala
     */
    public function generateDailyCSR()
    {
        $csrSetting = CSRSetting::first();
        if (!$csrSetting) {
            Log::warning('CSR setting not found');
            return response()->json(['message' => 'CSR setting not found'], 404);
        }

        $this->calculateCSRSinceLastWithdrawal($csrSetting->percentage);
        Log::info('CSR generation completed at ' . now()->format('Y-m-d H:i:s'));

        return response()->json(['message' => 'CSR generation completed']);
    }

    /**
     * Mendapatkan saldo CSR yang available untuk distribusi
     */
    public function getAvailableBalance()
    {
        $totalWithdrawal = CSRWithdrawal::sum('amount');
        $totalDistribution = CSRDistribution::sum('amount');
        return $totalWithdrawal - $totalDistribution;
    }

    /**
     * Form tambah pemasukan manual
     */
    public function showCreateIncomeForm()
    {
        return view('pages.superadmin.forms.csr.create-income');
    }

    /**
     * Form tambah penyaluran
     */
    public function showCreateDistributionForm()
    {
        $availableBalance = $this->getAvailableBalance();
        return view('pages.superadmin.forms.csr.create-distribution', compact('availableBalance'));
    }

    /**
     * Mendapatkan saldo CSR yang belum ditarik (untuk informasi)
     */
    public function getUnwithdrawnBalance()
    {
        return CSRIncome::notWithdrawn()->sum('amount');
    }

    public function showDistributionDetail($id)
    {
        try {
            $csrData = CSRDistribution::findOrFail($id);
            // dd($csrData);

            // Cek apakah file exists
            $fileExists = false;
            if ($csrData->document_path) {
                $fileExists = Storage::disk('public')->exists($csrData->document_path);
            }

            return view('pages.superadmin.details.detail-tracking-csr', [
                'csrData' => $csrData,
                'fileExists' => $fileExists
            ]);


        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Data penyaluran tidak ditemukan']);
        }
    }

    /**
     * Cetak PDF detail penyaluran CSR
     */
    public function printDistributionPdf($id)
    {
        try {
            $csrData = CSRDistribution::findOrFail($id);

            $fileExists = false;
            if ($csrData->document_path) {
                $fileExists = Storage::disk('public')->exists($csrData->document_path);
            }

            $imageData = null;

            if ($csrData->document_path && Storage::disk('public')->exists($csrData->document_path)) {
                $path = Storage::disk('public')->path($csrData->document_path);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $imageData = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }

            $business = Business::find(1);
            $letterhead = $business->getLetterheadData();

            return pdf()->view('pages.superadmin.pdf.csr-distribution-print', [
                    'csrData' => $csrData,
                    'fileExists' => $fileExists,
                    'image' => $imageData,
                    'letterhead' => $letterhead,    

                ])
                ->name('bukti-penyaluran-' . date('y-m-d', strtotime($csrData->date)) . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal mencetak PDF']);
        }
    }
}
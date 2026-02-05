<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\Business;
use App\Models\User;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = Cache::remember('all_users', 60, function () {
                return User::select('id', 'username', 'created_at', 'slug')
                    ->where('slug', '!=', Auth::user()->slug)
                    ->with([
                        'businesses' => function ($query) {
                            $query->select('businesses.id', 'name');
                        }
                    ])
                    ->get();
            });

            $allBusinessNames = $users->pluck('businesses')
                ->flatten()
                ->pluck('name')
                ->unique();

            $colors = $this->setColorToBadge($allBusinessNames);


            return view('pages.superadmin.manajemen-admin', compact('users', 'colors'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Admin');
            return view('pages.superadmin.manajemen-admin')->with('error', 'Kesalahan pada server.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $businesses = Business::select('id', 'name')->get();
            $transactionDateFormatted = Carbon::now()
                ->isoFormat('dddd, D MMMM Y');

            return view('pages.superadmin.forms.manajemen-admin.create', compact('businesses', 'transactionDateFormatted'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Admin');
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $user = User::create([
                'username' => $validated['username'],
                'password' => $validated['password'],
                'slug' => Str::slug($validated['username']),
                'role' => $validated['role']
            ]);

            if ($validated['role'] !== 'superadmin') {
                $user->businesses()->sync($validated['businesses']);
            }

            if (!$user) {
                notify()->error('Admin gagal ditambahkan!', 'Registrasi Admin');

                return redirect()->back();
            }

            Cache::forget('all_users');
            Cache::forget('all_businesses');

            notify()->success('Berhasil menambahkan admin.', 'Registrasi Admin');
            DB::commit();
            return redirect('/superadmin/manajemen-admin');
        } catch (\Exception $ex) {
            DB::rollBack();
            notify()->error('Kesalahan pada server.', 'Manajemen Admin');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        try {
            $user = User::where('slug', $slug)->first();
            $businesses = Business::select('id', 'name')->get();
            $selectedBusinesses = $user->businesses->pluck('id')->toArray();


            return view('pages.superadmin.forms.manajemen-admin.edit', compact('user', 'businesses', 'selectedBusinesses'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Admin');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $slug)
    {
        DB::beginTransaction();
        try {
            $user = User::where('slug', $slug)->first();

            if (!$user) {
                notify()->error('Admin tidak ditemukan!', 'Manajemen Admin');

                return redirect()->back();
            }

            $data = $request->only(['username', 'role']);

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $update = $user->update($data);

            if (!$update) {
                notify()->error('Gagal melakukan perubahan!', 'Manajemen Admin');

                return redirect()->back();
            }

            if ($request->filled('businesses')) {
                $user->businesses()->sync($request->businesses);
            } else {
                $user->businesses()->sync([]);
            }

            Cache::forget('all_users');
            Cache::forget('all_businesses');

            notify()->success('Berhasil melakukan perubahan data.', 'Manajemen Admin');
            DB::commit();
            return redirect('superadmin/manajemen-admin');
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Admin');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        try {
            $user = User::where('slug', $slug)->first();

            if (!$user) {
                notify()->error('Data admin tidak ditemukan!', 'Manajemen Admin');

                return redirect()->back();
            }

            $user->businesses()->detach();
            $user->delete();

            Cache::forget('all_users');
            Cache::forget('all_businesses');

            notify()->success('Berhasil menghapus data admin.', 'Manajemen Admin');
            return redirect()->back();
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Manajemen Admin');
            return redirect()->back();
        }

    }

    private function setColorToBadge($businessNames)
    {
        $colors = [
            'bg-gray-200',
            'bg-gray-300',
            'bg-slate-200',
            'bg-slate-300',
            'bg-zinc-200',
            'bg-zinc-300',
            'bg-stone-200',
            'bg-stone-300',
        ];

        $usedColors = [];
        $badgeMap = [];

        foreach ($businessNames as $name) {
            $availableColors = array_values(array_diff($colors, $usedColors));

            if (count($availableColors) === 0) {
                $usedColors = [];
                $availableColors = $colors;
            }

            $randomColor = $availableColors[array_rand($availableColors)];

            $badgeMap[$name] = $randomColor;
            $usedColors[] = $randomColor;
        }

        return $badgeMap;
    }
}

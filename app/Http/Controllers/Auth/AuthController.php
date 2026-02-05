<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Business;
use Auth;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private function roleCheck($role)
    {
        return match ($role) {
            'superadmin' => redirect('superadmin/dashboard'),
            'admin' => redirect('admin/dashboard'),
            default => redirect('/login')
        };
        // anotation
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('username', 'password');


            if (!Auth::attempt($credentials)) {
                notify()
                    ->error()
                    ->title('Gagal Login!')
                    ->send();

                return redirect('/login');
            }

            $request->session()->regenerate();

            notify()
                ->success()
                ->title('Berhasil Login!')
                ->send();

            if (Auth::user()->role === 'superadmin') {
                return redirect('/superadmin/dashboard');
            } else if (Auth::user()->role === 'admin') {
                $business = Business::whereHas('users', function ($q) {
                    $q->where('users.id', Auth::user()->id);
                })->select('slug')->first();

                return redirect()->route('admin.dashboard', ['businesses' => $business]);
            }
        } catch (Exception $ex) {
            notify()
                ->error()
                ->title('Terjadi kesalahan: ' . $ex->getMessage())
                ->send();
            return redirect()->back();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        notify()
            ->success()
            ->title('Berhasil Logout!')
            ->send();
        return redirect('/login');
    }

}

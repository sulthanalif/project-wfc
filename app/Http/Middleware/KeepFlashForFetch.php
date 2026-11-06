<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// order-draft.js mengirim form lewat fetch; fetch mengikuti redirect sehingga flash 'success'
// terpakai di request itu. Simpan lagi untuk halaman yang dibuka sesudahnya.
class KeepFlashForFetch
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET') && $request->hasHeader('X-Order-Fetch') && $request->session()->has('success')) {
            $request->session()->reflash();
        }

        return $next($request);
    }
}

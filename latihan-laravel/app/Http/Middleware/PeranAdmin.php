<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PeranAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pengguna = $request->user();

        if (! $pengguna || $pengguna->peran !== 'admin') {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Akses ditolak. Hanya admin yang dapat melakukan tindakan ini.',
            ], 403);
        }

        return $next($request);
    }
}

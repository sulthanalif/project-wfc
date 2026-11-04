<?php

namespace App\Http\Controllers;

use App\Models\Administration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class GetImageController extends Controller
{
    public function displayImage($path, $imageName)
    {
        $safePath = basename($path);
        $safeImageName = basename($imageName);

        if ($safePath !== $path || $safeImageName !== $imageName) {
            abort(400, 'Bad Request');
        }

        // Dokumen sensitif (KTP dan Surat Perjanjian) memerlukan otentikasi dan otorisasi
        if ($safePath === 'administration') {
            if (!auth()->check()) {
                abort(403, 'Unauthorized');
            }

            $user = auth()->user();
            $isAdmin = $user->hasAnyRole(['super_admin', 'admin']);
            $isOwner = false;

            if ($user->hasRole('agent')) {
                $isOwner = Administration::where('user_id', $user->id)
                    ->where(function ($q) use ($safeImageName) {
                        $q->where('ktp', $safeImageName)
                            ->orWhere('sPerjanjian', $safeImageName);
                    })->exists();
            }

            if (!$isAdmin && !$isOwner) {
                abort(403, 'Unauthorized');
            }
        }

        $fullPath = storage_path('app/public/images/' . $safePath . '/' . $safeImageName);
        if (!File::exists($fullPath)) {
            abort(404);
        }

        $file = File::get($fullPath);
        $type = File::mimeType($fullPath);

        $response = Response::make($file, 200);
        $response->header('Content-Type', $type);

        return $response;
    }
}


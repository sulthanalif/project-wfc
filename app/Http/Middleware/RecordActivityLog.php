<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RecordActivityLog
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        try {
            // Hanya catat request mutasi data (POST, PUT, PATCH, DELETE) agar database tidak membengkak
            if ($request->isMethod('GET') || $request->isMethod('HEAD') || $request->isMethod('OPTIONS')) {
                return;
            }

            // Ignore asset routes and image serving
            $path = $request->path();
            if (str_starts_with($path, 'storage/images') ||
                str_starts_with($path, 'assets') ||
                str_ends_with($path, '.css') ||
                str_ends_with($path, '.js') ||
                str_ends_with($path, '.png') ||
                str_ends_with($path, '.jpg') ||
                str_ends_with($path, '.ico')) {
                return;
            }

            $user = Auth::user();
            $userName = null;
            $userRole = null;

            if ($user) {
                $userName = $user->agentProfile?->name
                    ?? $user->adminProfile?->name
                    ?? $user->name
                    ?? $user->email;
                $userRole = $user->roles->first()?->name ?? 'user';
            } else {
                $userName = 'Guest';
                $userRole = 'guest';
            }

            $routeName = $request->route()?->getName();
            $method = $request->method();

            // Sanitize payload
            $inputs = $request->except([
                'password',
                'password_confirmation',
                'current_password',
                'newPass',
                'confirmPass',
                '_token',
                '_method',
            ]);

            $safePayload = [];
            foreach ($inputs as $key => $value) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $safePayload[$key] = is_array($file)
                        ? '[Array of Files]'
                        : ('[File: ' . $file->getClientOriginalName() . ' (' . round($file->getSize() / 1024) . ' KB)]');
                } elseif (is_string($value) && strlen($value) > 1000) {
                    $safePayload[$key] = substr($value, 0, 1000) . '... [truncated]';
                } else {
                    $safePayload[$key] = $value;
                }
            }

            $actionDescription = $routeName
                ? ('Route: ' . $routeName)
                : ($method . ' /' . ltrim($path, '/'));

            ActivityLog::create([
                'user_id' => $user?->id,
                'user_name' => $userName,
                'user_role' => $userRole,
                'method' => $method,
                'url' => $request->fullUrl(),
                'route_name' => $routeName,
                'action' => $actionDescription,
                'status_code' => $response->getStatusCode(),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'payload' => !empty($safePayload) ? $safePayload : null,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('ActivityLog error: ' . $e->getMessage());
        }
    }
}

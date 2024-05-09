<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class PaginationHelper
{
    public static function paginate(array $data, int $perPage, string $routeName): array
    {
        $currentPage = Request::get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $totalPage = ceil(count($data) / $perPage);

        $data = array_slice($data, $offset, $perPage);

        $totalData = count($data);

        $canPrevious = $currentPage > 1;
        $canNext = $currentPage < $totalPage;

        $nextUrl = null;
        $prevUrl = null;

        if ($canNext) {
            $nextUrl = route($routeName, ['page' => $currentPage + 1]);
        }

        if ($canPrevious) {
            $prevUrl = route($routeName, ['page' => $currentPage - 1]);
        }

        return compact('data', 'perPage', 'totalData', 'currentPage', 'totalPage', 'canPrevious', 'canNext', 'nextUrl', 'prevUrl');
    }
}

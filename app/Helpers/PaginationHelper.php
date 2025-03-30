<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationHelper
{
    const DEFAULT_SIZE = 10;

    public static function paginationDetails(LengthAwarePaginator $pagination): array
    {
        return [
            'count'       => $pagination->count(),
            'first'       => $pagination->currentPage() === 1,
            'last'        => $pagination->lastPage() === $pagination->currentPage(),
            'page'        => $pagination->currentPage(),
            'size'        => $pagination->perPage(),
            'total_count' => $pagination->total(),
            'total_pages' => $pagination->lastPage(),
        ];
    }
}

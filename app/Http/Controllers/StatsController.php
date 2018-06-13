<?php

namespace App\Http\Controllers;

use App\Logs;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Compute and send stats
     */
    public function getStats()
    {
        // /!\ This file is not well architecture. Queries should be separated from controller /!\
        // Here we need to multiply by -1 because sale log are negative good movement.
        // In other world, negative log are a good thing ^^
        return response([
            'max_sale_price' => DB::table(Logs::TABLE_NAME)
                ->min('total_price') * -1,
            'max_sale_quantity' => DB::table(Logs::TABLE_NAME)
                ->min('quantity') * -1,
            'total_sales_price' => DB::table(Logs::TABLE_NAME)
                ->selectRaw('SUM(total_price) as total_sales')
                ->get()[0]->total_sales,
        ]);
    }
}

<?php

namespace App\Listeners;

use App\Events\UpdateStock;
use App\Logs;
use App\Notifications\AlertLowStock;
use App\User;
use Illuminate\Support\Facades\Auth;

class UpdateStockListener
{
    /**
     * Handle the event by send mail if the new product stock is too low.
     * The method add a transaction log for stats computation.
     *
     * @param  UpdateStock $objEvent
     *
     * @return void
     */
    public function handle(UpdateStock $objEvent)
    {
        // Get the diff of quantity if exists
        $intQuantityDiff = $objEvent->arrUpdatedFields['quantity_diff'] ?? 0;
        // Get the product subject of the event
        $objProduct = $objEvent->objProduct;
        // If the quantity has been modified, we create a log
        if ($intQuantityDiff !== 0) {
            /** @var Logs $objLogs */
            $objLogs = Logs::create(
                [
                    'quantity' => $intQuantityDiff,
                    'unit_price' => $objProduct->unit_price,
                    'product_id' => $objProduct->id,
                ]
            );
            $objLogs->updateTotalPrice();
        }
        if ($objProduct->isLowStock($intQuantityDiff)) {
            /** @var User $objUser */
            $objUser = Auth::user();
            $objUser->notify(new AlertLowStock($objProduct));
        }
    }
}

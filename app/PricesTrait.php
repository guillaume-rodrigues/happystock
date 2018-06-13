<?php

namespace App;


trait PricesTrait
{
    /**
     * Compute the total price of the current product stock
     *
     * @return float
     */
    public function computeTotalPrice()
    {
        return $this->unit_price * $this->quantity;
    }

    /**
     * Update the total price of the current product stock
     *
     * @return float
     */
    public function updateTotalPrice()
    {
        $this->total_price = $this->computeTotalPrice();
        $this->save();

        return $this->total_price;
    }
}
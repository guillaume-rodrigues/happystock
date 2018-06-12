<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string name
 * @property string desc
 * @property integer quantity
 */
class Product extends Model
{
    const TABLE_NAME = 'products';
    // Limit used to trigger the "Alert low stock" email
    const QUANTITY_LIMIT = 10;

    protected $fillable = ['name', 'desc', 'quantity'];

    /**
     * Return true if the quantity of the current product is to low
     * @return bool
     */
    public function isLowStock()
    {
        return $this->quantity < self::QUANTITY_LIMIT;
    }
}

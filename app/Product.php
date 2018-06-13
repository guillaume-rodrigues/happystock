<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string name
 * @property string desc
 * @property integer quantity
 * @property float unit_price
 * @property float total_price
 */
class Product extends Model
{
    use PricesTrait;

    const TABLE_NAME = 'products';
    // Limit used to trigger the "Alert low stock" email
    const QUANTITY_LIMIT = 10;
    // Field names
    const FIELD_NAME = 'name';
    const FIELD_DESC = 'desc';
    const FIELD_QUANTITY = 'quantity';
    const FIELD_QUANTITY_DIFF = 'quantity_diff';
    const FIELD_UNIT_PRICE = 'unit_price';
    const FIELD_TOTAL_PRICE = 'total_price';

    protected $fillable = [
        self::FIELD_NAME,
        self::FIELD_DESC,
        self::FIELD_QUANTITY,
        self::FIELD_UNIT_PRICE,
        self::FIELD_TOTAL_PRICE,
    ];

    /**
     * Return true if the quantity of the current product is too low.
     * The quantity is too low when the current quantity is under limit
     * and the old quantity (current - diff) is upper the limit
     *
     * @param int $intQuantityDiff
     * @return bool
     */
    public function isLowStock($intQuantityDiff)
    {
        return $this->quantity < self::QUANTITY_LIMIT && $this->quantity - $intQuantityDiff >= self::QUANTITY_LIMIT;
    }
}

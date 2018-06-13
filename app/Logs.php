<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Logs
 * @package App
 * @property int id
 * @property int quantity
 * @property float price
 */
class Logs extends Model
{
    use PricesTrait;

    const TABLE_NAME = 'logs';
    const FIELD_QUANTITY = 'quantity';
    const FIELD_UNIT_PRICE = 'unit_price';
    const FIELD_TOTAL_PRICE = 'total_price';
    const FIELD_PRODUCT_ID = 'product_id';

    protected $fillable = [
        self::FIELD_QUANTITY,
        self::FIELD_UNIT_PRICE,
        self::FIELD_TOTAL_PRICE,
        self::FIELD_PRODUCT_ID,
    ];
}

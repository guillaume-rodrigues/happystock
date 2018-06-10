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

    protected $fillable = ['name', 'desc', 'quantity'];
}

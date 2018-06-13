<?php

namespace App\Events;

use App\Product;
use Illuminate\Queue\SerializesModels;

class UpdateStock
{
    use SerializesModels;

    /** @var Product */
    public $objProduct;
    /** @var array */
    public $arrUpdatedFields;

    /**
     * Create a new event instance.
     *
     * @param Product $objProduct
     * @param array $arrUpdatedFields
     * @return void
     */
    public function __construct($objProduct, $arrUpdatedFields)
    {
        $this->objProduct = $objProduct;
        $this->arrUpdatedFields = $arrUpdatedFields;
    }
}

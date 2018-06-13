<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();

        $objFakerGenerator = \Faker\Factory::create();

        for ($intIndex = 0; $intIndex < 50; $intIndex++) {
            /** @var Product $objProduct */
            $objProduct = Product::create([
                'name' => $objFakerGenerator->sentence,
                'desc' => $objFakerGenerator->paragraph,
                'quantity' => $objFakerGenerator->numberBetween(0, 100),
                'unit_price' => $objFakerGenerator->randomFloat(2, 1, 1000),
            ]);
            $objProduct->updateTotalPrice();
        }
    }
}

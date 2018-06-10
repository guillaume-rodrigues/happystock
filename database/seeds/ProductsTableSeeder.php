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
            Product::create([
                'name' => $objFakerGenerator->sentence,
                'desc' => $objFakerGenerator->paragraph,
                'quantity' => $objFakerGenerator->numberBetween(0, 100)
            ]);
        }
    }
}

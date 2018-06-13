<?php

use Illuminate\Database\Seeder;
use App\Logs;

class LogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Logs::truncate();

        $objFakerGenerator = \Faker\Factory::create();

        for ($intIndex = 0; $intIndex < 150; $intIndex++) {
            // In this faker, log will not be consistent with product.
            // In other world, unit_price will not be the same between a logs and a products.
            /** @var Logs $objLogs */
            $objLogs = Logs::create([
                'quantity' => $objFakerGenerator->numberBetween(-100, 100),
                'unit_price' => $objFakerGenerator->randomFloat(2, 1, 1000),
                'product_id' => $objFakerGenerator->numberBetween(0, 49),
            ]);
            $objLogs->updateTotalPrice();
        }
    }
}

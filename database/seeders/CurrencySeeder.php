<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                "key" => 'dollar',
                'name' => 'Dollar',
                'symbol' => '$',
                'iso_code' => 'usd',
            ],
            [
                "key" => 'yuan',
                'name' => 'Yuan',
                'symbol' => '¥',
                'iso_code' => 'cny',
            ],
            [
                "key" => 'pound',
                'name' => 'Pound',
                'symbol' => '£',
                'iso_code' => 'gbp',
            ],
            [
                "key" => 'rial',
                'name' => 'Dollar',
                'symbol' => '﷼',
                'iso_code' => 'irr',
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}

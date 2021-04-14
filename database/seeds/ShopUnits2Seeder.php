<?php

use Illuminate\Database\Seeder;

class ShopUnits2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('shop_units')->insert([
        [
          'name' => 'м',
          'type' => 'float'
        ],
        [
          'name' => 'см',
          'type' => 'float'
        ],
        [
          'name' => 'мм',
          'type' => 'float'
        ],
        [
          'name' => 'г',
          'type' => 'float'
        ],
        [
          'name' => '"',
          'type' => 'float'
        ],
        [
          'name' => 'МГц',
          'type' => 'float'
        ],
        [
          'name' => 'ГГц',
          'type' => 'float'
        ],
        [
          'name' => 'МБ',
          'type' => 'float'
        ],
        [
          'name' => 'ГБ',
          'type' => 'float'
        ],
        [
          'name' => 'ТБ',
          'type' => 'float'
        ],
        [
          'name' => 'мА*ч',
          'type' => 'float'
        ],
        [
          'name' => 'ppi',
          'type' => 'float'
        ],
        [
          'name' => 'Мп',
          'type' => 'float'
        ],
        [
          'name' => 'п',
          'type' => 'float'
        ],
        [
          'name' => 'Кд/м²',
          'type' => 'float'
        ],
        [
          'name' => 'Вт',
          'type' => 'float'
        ],
        [
          'name' => 'ч',
          'type' => 'float'
        ],
        [
          'name' => 'м',
          'type' => 'float'
        ],
        [
          'name' => 'дБ',
          'type' => 'float'
        ],
      ]);
    }
}

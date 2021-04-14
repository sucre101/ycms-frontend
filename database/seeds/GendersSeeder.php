<?php

use Illuminate\Database\Seeder;

class GendersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('genders')->insert([
        [
          'title' => 'male',
        ],
        [
          'title' => 'female',
        ],
        [
          'title' => 'transgender',
        ],
        [
          'title' => 'gender neutral',
        ],
        [
          'title' => 'non-binary',
        ],
        [
          'title' => 'agender',
        ],
        [
          'title' => 'pangender',
        ],
        [
          'title' => 'genderqueer',
        ],
        [
          'title' => 'two-spirit',
        ],
        [
          'title' => 'third gender',
        ],
      ]);
    }
}

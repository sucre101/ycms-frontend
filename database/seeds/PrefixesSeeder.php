<?php

use Illuminate\Database\Seeder;

class PrefixesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('name_prefix')->insert([
        [
          'title' => '1st Lt',
          'title_short' => 'First Lieutenant'
        ],
        [
          'title' => 'Adm',
          'title_short' => 'Admiral'
        ],
        [
          'title' => 'Atty',
          'title_short' => 'Attorney'
        ],
        [
          'title' => 'Brother',
          'title_short' => 'Brother (religious)'
        ],
        [
          'title' => 'Capt',
          'title_short' => 'Captain'
        ],
        [
          'title' => 'Chief',
          'title_short' => 'Chief'
        ],
        [
          'title' => 'Cmdr',
          'title_short' => 'Commander'
        ],
        [
          'title' => 'Col',
          'title_short' => 'Colonel'
        ],
        [
          'title' => 'Dean',
          'title_short' => 'University Dean'
        ],
        [
          'title' => 'Dr',
          'title_short' => 'Doctor'
        ],
        [
          'title' => 'Elder',
          'title_short' => 'Elder (religious)'
        ],
        [
          'title' => 'Father',
          'title_short' => 'Father (religious)'
        ],
        [
          'title' => 'Gen',
          'title_short' => 'General'
        ],
        [
          'title' => 'Gov',
          'title_short' => 'Governor'
        ],
        [
          'title' => 'Hon',
          'title_short' => 'Honorable'
        ],
        [
          'title' => 'Lt Col',
          'title_short' => 'Lieutenant Colonel'
        ],
        [
          'title' => 'Mj',
          'title_short' => 'Major'
        ],
        [
          'title' => 'MSgt',
          'title_short' => 'Major/Master Sergeant'
        ],
        [
          'title' => 'Mr',
          'title_short' => 'Mister'
        ],
        [
          'title' => 'Mrs',
          'title_short' => 'Married Woman'
        ],
        [
          'title' => 'Ms',
          'title_short' => 'Single or Married Woman'
        ],
        [
          'title' => 'Prince',
          'title_short' => 'Prince'
        ],
        [
          'title' => 'Professor',
          'title_short' => 'Professor'
        ],
        [
          'title' => 'Rabbi',
          'title_short' => 'Rabbi (religious)'
        ],
        [
          'title' => 'Reverend',
          'title_short' => 'Reverend (religious)'
        ],
        [
          'title' => 'Sister',
          'title_short' => 'Sister'
        ],
      ]);
    }
}

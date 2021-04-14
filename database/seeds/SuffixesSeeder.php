<?php

use Illuminate\Database\Seeder;

class SuffixesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('name_suffix')->insert([
        [
          'title' => 'II',
          'title_short' => 'The Second'
        ],
        [
          'title' => 'III',
          'title_short' => 'The Third'
        ],
        [
          'title' => 'IV',
          'title_short' => 'The Fourth'
        ],
        [
          'title' => 'CPA',
          'title_short' => 'Certified Public Accountant'
        ],
        [
          'title' => 'DDS',
          'title_short' => 'Doctor of Dental Medicine'
        ],
        [
          'title' => 'Esq',
          'title_short' => 'Esquire'
        ],
        [
          'title' => 'JD',
          'title_short' => 'Jurist Doctor'
        ],
        [
          'title' => 'Jr',
          'title_short' => 'Junior'
        ],
        [
          'title' => 'LLD',
          'title_short' => 'Doctor of Laws'
        ],
        [
          'title' => 'MD',
          'title_short' => 'Doctor of Medicine'
        ],
        [
          'title' => 'PhD',
          'title_short' => 'Doctorate'
        ],
        [
          'title' => 'Ret',
          'title_short' => 'Retired from Armed Forces'
        ],
        [
          'title' => 'RN',
          'title_short' => 'Registered Nurse'
        ],
        [
          'title' => 'Sr',
          'title_short' => 'Senior'
        ],
        [
          'title' => 'DO',
          'title_short' => 'Doctor of Osteopathy'
        ],
      ]);
    }
}

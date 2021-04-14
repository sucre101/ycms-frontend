<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run () {
    DB::table('users')
      ->insert([
      [
        'name' => 'Test testovich',
        'email' => 'test@yappix.ru',
        'password' => '$2y$10$9o5FHs5.jqV/Ea.cI9W9ZOsD.HEtFHslV2.1Kk6Kmcc/w/I14GTl6', // 123123
        'created_at' => '2020-06-07 17:28:19',
        'updated_at' => '2020-06-07 17:28:19',
        'role' => 'mobile-user',
      ]
    ]);
  }
}

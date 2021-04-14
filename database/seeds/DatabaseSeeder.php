<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
//        $this->call(AppsTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
//        $this->call(UsersTableSeeder::class);
//        $this->call(PagesTableSeeder::class);
        $this->call(ShopUnitSeeder::class);
        $this->call(ShopUnits2Seeder::class);
        $this->call(ShopOrderStatuses::class);
//        $this->call(ShopSettingSeeder::class);
        $this->call(PrefixesSeeder::class);
        $this->call(SuffixesSeeder::class);
        $this->call(GendersSeeder::class);
    }
}

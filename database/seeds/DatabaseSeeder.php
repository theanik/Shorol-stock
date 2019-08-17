<?php

use Illuminate\Database\Seeder;
use App\Customer;
use App\Category;
use App\Product;
use App\Supplier;
use App\Purchase;
use App\Sales;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        factory(Customer::class,40)->create();
        factory(Category::class,25)->create();
        factory(Product::class,35)->create();
        factory(Supplier::class,30)->create();
        factory(Purchase::class,20)->create();
        factory(Sales::class,20)->create();
    }
}

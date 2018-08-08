<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Category;
use App\Product;
use App\Transaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Vaciando tabla antes de llenarla de nuevo
         */
        $this->truncateTables([
            'users',
            'categories',
            'products',
            'transactions',
            'category_product'
        ]);

        // DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        // User::truncate();
        // Category::truncate();
        // Product::truncate();
        // Transaction::truncate();
        // DB::table('category_product')->truncate();

        //evitar que los eventos asociados a los modelos se ejecuten
        User::flushEventListeners();
        Product::flushEventListeners();
        Category::flushEventListeners();
        Transaction::flushEventListeners();

        /*Llamando factory */
        factory(User::class, 10)->create();
        factory(Category::class, 10)->create();

        factory(Product::class, 10)->create()->each(
            //agregando categorias a cada producto
            function ($producto){
                $categorias = Category::all()->random(mt_rand(1,5))->pluck('id');
                $producto->categories()->attach($categorias);
            }
        );

        factory(Transaction::class, 10)->create();
    }

    protected function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;'); //ignorar llaves foraneas

        foreach($tables as $table){
            DB::table($table)->truncate(); //vaciar la tabla
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;'); //reactivar validacion dellave foranea
    }    
}

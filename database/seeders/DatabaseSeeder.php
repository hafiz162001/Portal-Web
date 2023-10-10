<?php

namespace Database\Seeders;

// use App\Models\CategoryArticle;
// use App\Models\ChannelArticle;
use Illuminate\Database\Seeder;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Laravolt\Indonesia\Seeds\VillagesSeeder;
use Laravolt\Indonesia\Seeds\DistrictsSeeder;
use Laravolt\Indonesia\Seeds\ProvincesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // ProvincesSeeder::class,
            // CitiesSeeder::class,
            // DistrictsSeeder::class,
            // VillagesSeeder::class,
            UserSeeder::class,
// <<<<<<< HEAD
            FestivalSeeder::class,
            // ArticleSeeder::class,
// =======
            // FestivalSeeder::class,
            ArticleSeeder::class
// >>>>>>> 4af0e938b1f3c2f26a565cc1d3982d6fc256feea
            // ChannelArticle::class,
            // CategoryArticle::class,
        ]);
    }
}

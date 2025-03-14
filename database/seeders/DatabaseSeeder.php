<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $faker = Faker::create('fr_FR');

      //  foreach (range(1, 5) as $_) {

          //  DB::table('authors')->insert([
          //      'name' => $faker->firstName(),
           //     'surname' => $faker->lastName(),
         //   ]);
      //  }
        $category = ['Movie', 'Music', 'Sport', 'Animals', 'Vacation'];
        foreach (range(1, 20) as $_) {
            $cat = $category[rand(0, count($category) - 1)];

            DB::table('posts')->insert([
                'title' => Str::limit($faker->sentence(), 30, ''),
                'body' => $faker->paragraph(),
                'slug' => $faker->slug() ,
                'category' => $cat,
                'status' => 'published',
                'author_id' => rand(1, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }






        // User::factory(10)->create();

        // User::factory()->create([
        //  'name' => 'Test User',
        // 'email' => 'test@example.com',
        // ]);
    }
}

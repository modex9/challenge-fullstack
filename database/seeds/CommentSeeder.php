<?php

use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Run factory a few times to form a comment tree.
        for($i = 0; $i < 10; $i++) {
            factory(App\Comment::class, 5)->create();
        }
    }
}

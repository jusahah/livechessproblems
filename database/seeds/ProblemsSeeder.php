<?php

use Illuminate\Database\Seeder;

class ProblemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('problems')->insert([
            'name' => 'Ratsun koukku',
            'position' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6 0 2',
            'solution' => 'a2-a4',
            'difficulty' => 3,
            'collection_id' => 1
        ]);

    }
}

<?php

use Illuminate\Database\Seeder;

class CollectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    /* Luo Collections table*/
    /* Collection on kokoelma shakkitehtäviä*/

    public function run()
    {
        DB::table('collections')->insert([
            'name' => 'Joukon probleemat',
            'description' => "Paljon helppoja ja puolivaikeita tehtäviä, joiden kanssa viihtyy itse kukin omalla tavallaan. Vaikeusaste vaihtelee roisilla kädellä.",
            'secret' => 'xxx1',
            
        ]);
    }

    
}






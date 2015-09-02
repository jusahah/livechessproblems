<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Tournament extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tournaments';


    public function collection() {
        return $this->belongsTo('App\Collection');
    }

    public function problems() {
        return $this->collection()->problems();
    }



    


}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'problems';

    public $timestamps = ["created_at"];


    public function collection() {
        return $this->belongsTo('App\Collection');
    }

    

    


}

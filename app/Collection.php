<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Collection extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'collections';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['secret'];


    public function problems() {
        return $this->hasMany('App\Problem');
    }

    


}

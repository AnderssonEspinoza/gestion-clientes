<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'email', 'telefono', 'estado'];

    //
    public function asignacion(){
        return $this->hasOne(Asignacion::class);

    }

}

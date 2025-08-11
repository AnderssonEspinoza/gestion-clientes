<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $fillable = ['cliente_id', 'user_id'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}

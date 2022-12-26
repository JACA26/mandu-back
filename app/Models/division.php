<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Nombre de la tabla en MySQL.
class division extends Model
{
    use HasFactory;
    
    protected $table = 'division';
    
    protected $fillable = [
        'nombre',
        'nivel',
        'colaboradores',
        'embajador',
        'parent_id',
    ];
    
    protected $casts = [
        'parent_id' => 'integer',
    ];
    
    public function parent()
    {
        return $this->belongsTo(division::class, 'parent_id');
    }
}

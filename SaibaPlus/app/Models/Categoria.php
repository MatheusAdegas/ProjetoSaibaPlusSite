<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table    = 'categorias';
    public $timestamps = false;
    protected $fillable = ['nome', 'icone', 'cor', 'descricao'];

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'categoria_id');
    }
}
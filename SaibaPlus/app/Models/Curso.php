<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table    = 'cursos';
    public $timestamps = false;
    protected $fillable = [
        'categoria_id', 'titulo', 'descricao', 'nivel',
        'carga_horaria', 'instrutor', 'preco', 'matriculas', 'imagem_url',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table    = 'usuarios';
    public $timestamps = false;
    protected $fillable = [
        'nome', 'cpf', 'email', 'login', 'senha',
        'telefone', 'nascimento', 'escolaridade', 'area_interesse',
        'endereco', 'bairro', 'cidade', 'estado', 'cep',
    ];

    protected $hidden = ['senha'];

    public function tokens()
    {
        return $this->hasMany(Token::class, 'usuario_id');
    }
}
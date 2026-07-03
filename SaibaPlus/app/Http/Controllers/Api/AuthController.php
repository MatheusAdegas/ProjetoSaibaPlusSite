<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // POST /api/usuarios
    public function register(Request $request)
    {
        $request->validate([
            'nome'           => 'required|string|max:200',
            'cpf'            => 'required|string|max:14|unique:usuarios,cpf',
            'email'          => 'required|email|unique:usuarios,email',
            'login'          => 'required|string|max:80|unique:usuarios,login',
            'senha'          => 'required|string|min:8',
            'telefone'       => 'nullable|string|max:20',
            'nascimento'     => 'nullable|date',
            'escolaridade'   => 'nullable|string|max:50',
            'area_interesse' => 'nullable|string|max:50',
            'endereco'       => 'nullable|string|max:300',
            'bairro'         => 'nullable|string|max:100',
            'cidade'         => 'nullable|string|max:100',
            'estado'         => 'nullable|string|max:2',
            'cep'            => 'nullable|string|max:10',
        ]);

        $usuario = Usuario::create([
            'nome'           => $request->nome,
            'cpf'            => $request->cpf,
            'email'          => $request->email,
            'login'          => $request->login,
            'senha'          => Hash::make($request->senha),
            'telefone'       => $request->telefone,
            'nascimento'     => $request->nascimento,
            'escolaridade'   => $request->escolaridade,
            'area_interesse' => $request->area_interesse,
            'endereco'       => $request->endereco,
            'bairro'         => $request->bairro,
            'cidade'         => $request->cidade,
            'estado'         => $request->estado,
            'cep'            => $request->cep,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuário cadastrado com sucesso.',
            'data'    => $usuario->only(['id', 'nome', 'email', 'login']),
        ], 201);
    }

    // POST /api/auth/login
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'senha' => 'required|string',
        ]);

        // Aceita login por campo "login" ou por e-mail
        $usuario = Usuario::where('login', $request->login)
                          ->orWhere('email', $request->login)
                          ->first();

        if (!$usuario || !Hash::check($request->senha, $usuario->senha)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        // Apaga tokens antigos deste usuário
        Token::where('usuario_id', $usuario->id)->delete();

        // Gera novo token
        $token = Token::create([
            'usuario_id' => $usuario->id,
            'token'      => Str::random(64),
            'expires_at' => now()->addHours(8),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login realizado com sucesso.',
            'token'   => $token->token,
            'usuario' => $usuario->only(['id', 'nome', 'email', 'login']),
        ]);
    }

    // POST /api/auth/logout
    public function logout(Request $request)
    {
        $token = $request->header('Authorization');

        if ($token) {
            Token::where('token', $token)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado.',
        ]);
    }
}
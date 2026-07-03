<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    // GET /api/cursos?categoria_id=2
    public function index(Request $request)
    {
        $query = Curso::with('categoria');

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $cursos = $query->orderByDesc('matriculas')->get();

        return response()->json([
            'success' => true,
            'data'    => $cursos,
        ]);
    }

    // GET /api/cursos/{id}
    public function show($id)
    {
        $curso = Curso::with('categoria')->find($id);

        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso não encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $curso,
        ]);
    }
}
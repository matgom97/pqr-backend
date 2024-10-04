<?php
namespace App\Http\Controllers;

use App\Models\Pqr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

class PqrController extends Controller
{
     // Almacenar una nueva PQR
     public function store(Request $request)
     {
         // Verificar si el usuario está autenticado
         if (!Auth::check()) {
             return response()->json(['message' => 'Unauthorized'], 401);
         }
 
         Log::info('ID del usuario autenticado: ' . Auth::id());
 
         // Validación de datos
         $validated = $request->validate([
             'fecha_incidencia' => 'required|date',
             'identificacion' => 'required|string',
             'primer_nombre' => 'required|string',
             'primer_apellido' => 'required|string',
             'correo' => 'required|email',
             'medio_notificacion' => 'nullable|string',
             'tipo' => 'required|in:Petición,Queja,Reclamo',
             'causas' => 'nullable|string',
             'observacion' => 'required|string',
             'evidencias' => 'nullable|string', // O ajusta según cómo manejes las evidencias
         ]);
 
         // Crear nueva PQR
         $pqr = Pqr::create([
             'user_id' => Auth::id(),
             'fecha_incidencia' => $validated['fecha_incidencia'],
             'identificacion' => $validated['identificacion'],
             'primer_nombre' => $validated['primer_nombre'],
             'primer_apellido' => $validated['primer_apellido'],
             'correo' => $validated['correo'],
             'medio_notificacion' => $validated['medio_notificacion'],
             'tipo' => $validated['tipo'],
             'causas' => $validated['causas'],
             'observacion' => $validated['observacion'],
             'evidencias' => $validated['evidencias'],
         ]);
 
         // Mostrar el código de la petición registrada
         return response()->json([
             'message' => 'PQR registrada con éxito.',
             'codigo' => $pqr->id, // Puedes ajustar esto según cómo desees mostrar el código
             'pqr' => $pqr,
         ], 201);
     }

    // Listar todas las PQR (solo las del usuario si no es admin)
    public function index(Request $request)
    {
        // Obtener la consulta de PQR
        $query = Pqr::with('user');

        // Filtrar por fecha si se proporciona
        if ($request->has('fecha_incidencia')) {
            $query->whereDate('fecha_incidencia', $request->input('fecha_incidencia'));
        }

        // Filtrar por tipo si se proporciona
        if ($request->has('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        // Si el usuario no es administrador, filtrar por su ID
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        // Obtener las PQR filtradas o todas si no se aplican filtros
        $pqrs = $query->get();

        return response()->json($pqrs);
    }

    // Ver una PQR específica
    public function show($id)
    {
        // Obtener la PQR
        $pqr = Pqr::with('user')->findOrFail($id);

        // Verificar si el usuario es administrador o es el creador de la PQR
        if (!Auth::user()->isAdmin() && $pqr->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($pqr);
    }

    // Actualizar una PQR
    public function update(Request $request, $id)
    {
        // Validación de datos
        $validated = $request->validate([
            'fecha_incidencia' => 'required|date',
            'identificacion' => 'required|string',
            'primer_nombre' => 'required|string',
            'primer_apellido' => 'required|string',
            'correo' => 'required|email',
            'medio_notificacion' => 'required|string',
            'tipo' => 'required|in:Petición,Queja,Reclamo',
            'causas' => 'nullable|string', // Permitir campos opcionales
            'observacion' => 'required|string',
        ]);
    
        // Obtener la PQR
        $pqr = Pqr::findOrFail($id);
    
        // Verificar si el usuario es administrador o es el creador de la PQR
        if (!Auth::user()->isAdmin() && $pqr->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        // Actualizar la PQR con todos los campos validados
        $pqr->update($validated);
    
        return response()->json($pqr);
    }

    // Eliminar una PQR
    public function destroy($id)
    {
        // Obtener la PQR
        $pqr = Pqr::findOrFail($id);

        // Verificar si el usuario es administrador o es el creador de la PQR
        if (!Auth::user()->isAdmin() && $pqr->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Eliminar la PQR
        $pqr->delete();

        return response()->json(['message' => 'PQR eliminada con éxito.']);
    }

    
}
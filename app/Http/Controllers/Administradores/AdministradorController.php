<?php

namespace App\Http\Controllers\Administradores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador;
use App\Models\AIngreso;
use App\Models\ADestajo;
use App\Models\ACompra;
 use Redirect;
class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  public function index()
    {
        $administradores = Administrador::select('id', 'nombres', 'apellidos', 'mail' , 'passtemp' , 'principal', 'created_at')
            ->orderBy('nombres')
            ->get();
        
        $ingresos = AIngreso::select('id', 'nombres', 'apellidos', 'mail' , 'passtemp' , 'created_at')
            ->orderBy('nombres')
            ->get();
        
        $destajos = ADestajo::select('id', 'nombres', 'apellidos', 'mail' , 'passtemp' , 'created_at')
            ->orderBy('nombres')
            ->get();
        
        $compras = ACompra::select('id', 'nombres', 'apellidos', 'mail' , 'passtemp' , 'created_at')
            ->orderBy('nombres')
            ->get();
        
        return view('administradores.administradores.index', compact(
            'administradores', 
            'ingresos', 
            'destajos', 
            'compras'
        ));
    }

   
    public function create()
    {
        return view('administradores.administradores.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:150',
            'apellidos' => 'required|string|max:150',
            'mail' => 'required|email|max:50',
            'tipo_administrador' => 'required|in:administrador,aingresos,adestajos,acompras',
            'principal' => 'sometimes|boolean'
        ]);

        try {

            // Validar si el email ya existe en el sistema
            $validacionEmail = ValidarMail($request->mail);
            
            if ($validacionEmail['existe']) {
                $tabla = $validacionEmail['tabla'];
                $mensajeTabla = [
                    'administradores' => 'Administradores Generales',
                    'aingresos' => 'Administradores de Ingresos', 
                    'adestajos' => 'Administradores de Destajos',
                    'acompras' => 'Administradores de Compras'
                ][$tabla] ?? $tabla;
                
                return redirect()->back()
                    ->withInput()
                    ->with('error', "El correo electrónico ya está registrado en {$mensajeTabla}.");
            }
         
           
            
            // Generar contraseña temporal usando la función existente
            $passTemp = GenerarPass(); // Asumo que esta función ya existe
            
           
            
            // Datos comunes para todos los tipos
            $datosComunes = [
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'mail' => $validated['mail'],
                'pass' => '', 
                'passtemp' => $passTemp, // Guardamos la contraseña temporal en texto plano
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Crear el administrador según el tipo seleccionado
            switch ($validated['tipo_administrador']) {
                case 'administrador':
                    // Agregar campo principal si es administrador general
                    $datosComunes['principal'] = $request->has('principal') ? 1 : 0;
                    
                    $administrador = Administrador::create($datosComunes);
                    $tipo = 'Administrador General';
                    break;
                    
                case 'aingresos':
                    $administrador = Aingreso::create($datosComunes);
                    $tipo = 'Administrador de Ingresos';
                    break;
                    
                case 'adestajos':
                    $administrador = Adestajo::create($datosComunes);
                    $tipo = 'Administrador de Destajos';
                    break;
                    
                case 'acompras':
                    $administrador = Acompra::create($datosComunes);
                    $tipo = 'Administrador de Compras';
                    break;
                    
                default:
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Tipo de administrador no válido.');
            }

            // Aquí podrías enviar un email con la contraseña temporal
            // $this->enviarEmailContrasenaTemporal($administrador->mail, $passTemp, $tipo);
            
            // Mensaje de éxito con la contraseña temporal (para pruebas)
            $mensaje = "{$tipo} creado exitosamente. ";
            $mensaje .= "Contraseña temporal: <strong>{$passTemp}</strong> ";
            $mensaje .= "Esta contraseña debe ser cambiada en el primer inicio de sesión.";
            
            return redirect()->route('administradores.index')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            // Log del error
            \Log::error('Error al crear administrador: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el administrador: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    try {
        // Primero intentar encontrar en cada tabla de administradores
        $administrador = null;
        $tipo = '';
        
        // Buscar en Administradores Generales
        if ($admin = Administrador::find($id)) {
            $administrador = $admin;
            $tipo = 'administrador';
        }
        // Buscar en Administradores de Ingresos
        elseif ($ingreso = Aingreso::find($id)) {
            $administrador = $ingreso;
            $tipo = 'aingresos';
        }
        // Buscar en Administradores de Destajos
        elseif ($destajo = Adestajo::find($id)) {
            $administrador = $destajo;
            $tipo = 'adestajos';
        }
        // Buscar en Administradores de Compras
        elseif ($compra = Acompra::find($id)) {
            $administrador = $compra;
            $tipo = 'acompras';
        }
        
        // Si no se encontró en ninguna tabla
        if (!$administrador) {
            return redirect()->route('administradores.index')
                ->with('error', 'Administrador no encontrado.');
        }
        
        return view('administradores.administradores.show', compact('administrador', 'tipo'));
        
    } catch (\Exception $e) {
        return redirect()->route('administradores.index')
            ->with('error', 'Error al cargar el administrador: ' . $e->getMessage());
    }
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    try {
        // Validar datos básicos siempre requeridos
        $request->validate([
            'nombres' => 'required|string|max:150',
            'apellidos' => 'required|string|max:150',
            'tipo_original' => 'required|in:administrador,aingresos,adestajos,acompras',
            'principal' => 'sometimes|boolean',
            'email_changed' => 'required|in:0,1'
        ]);

        // Obtener tipo
        $tipo = $request->tipo_original;
        
        // Buscar administrador
        $administrador = $this->buscarAdministradorPorTipo($id, $tipo);
        
        if (!$administrador) {
            return redirect()->route('administradores.index')
                ->with('error', 'Administrador no encontrado.');
        }
        
        // Actualizar datos básicos
        $administrador->nombres = $request->nombres;
        $administrador->apellidos = $request->apellidos;
        
        // Verificar si el email fue cambiado
        $emailChanged = $request->email_changed === '1';
        
        if ($emailChanged && $request->has('mail')) {
            // Validar el email
            $request->validate([
                'mail' => 'required|email|max:50'
            ]);
            
            // Verificar si el nuevo email ya existe en el sistema
            $validacionEmail = ValidarMail($request->mail);
            
            if ($validacionEmail['existe']) {
                // Solo es error si no es el mismo registro
                if ($validacionEmail['modelo']->id != $id) {
                    $tabla = $validacionEmail['tabla'];
                    $mensajeTabla = [
                        'administradores' => 'Administradores Generales',
                        'aingresos' => 'Administradores de Ingresos', 
                        'adestajos' => 'Administradores de Destajos',
                        'acompras' => 'Administradores de Compras'
                    ][$tabla] ?? $tabla;
                    
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "El correo electrónico ya está registrado en {$mensajeTabla}.");
                }
            }
            
            // Actualizar el email
            $administrador->mail = $request->mail;
        }
        // Si el email no cambió, no hacemos nada con él
        // Se mantiene el email original automáticamente
        
        // Actualizar campo principal si es administrador general
        if ($tipo === 'administrador') {
            $administrador->principal = $request->has('principal') ? 1 : 0;
        }
        
        $administrador->save();
        
        // Mensaje según si el email cambió o no
        $mensaje = 'Administrador actualizado correctamente';
        if ($emailChanged) {
            $mensaje .= ' (email modificado)';
        } else {
            $mensaje .= ' (email sin cambios)';
        }
        
        return Redirect::back()
            ->with('success', $mensaje);
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
}

/**
 * Busca un administrador por ID en una tabla específica
 */
private function buscarAdministradorPorTipo($id, $tipo)
{
    $modelos = [
        'administrador' => Administrador::class,
        'aingresos' => Aingreso::class,
        'adestajos' => Adestajo::class,
        'acompras' => Acompra::class
    ];
    
    if (!isset($modelos[$tipo])) {
        return null;
    }
    
    return $modelos[$tipo]::find($id);
}

    /**
 * Remove the specified resource from storage.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function destroy($id)
{
    try {
        // Buscar en qué tabla está el usuario
        $usuario = null;
        $tipo = '';
        $modelo = null;
        
        // Buscar en todas las tablas
        if ($admin = Administrador::find($id)) {
            $usuario = $admin;
            $tipo = 'administrador';
            $modelo = Administrador::class;
        } 
        elseif ($ingreso = AIngreso::find($id)) {
            $usuario = $ingreso;
            $tipo = 'aingresos';
            $modelo = AIngreso::class;
        }
        elseif ($destajo = ADestajo::find($id)) {
            $usuario = $destajo;
            $tipo = 'adestajos';
            $modelo = ADestajo::class;
        }
        elseif ($compra = ACompra::find($id)) {
            $usuario = $compra;
            $tipo = 'acompras';
            $modelo = ACompra::class;
        }
        
        // Si no se encontró en ninguna tabla
        if (!$usuario) {
            return redirect()->route('administradores.index')
                ->with('error', 'Usuario no encontrado.');
        }
        
        // Verificar si es administrador principal (solo para tipo administrador)
        if ($tipo === 'administrador' && $usuario->principal == 1) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar al administrador principal del sistema.');
        }
        
        // Obtener información para el mensaje
        $nombreCompleto = $usuario->nombres . ' ' . $usuario->apellidos;
        $email = $usuario->mail;
        
        // Determinar el tipo de usuario para el mensaje
        $tipoUsuario = [
            'administrador' => 'Administrador General',
            'aingresos' => 'Administrador de Ingresos',
            'adestajos' => 'Administrador de Destajos',
            'acompras' => 'Administrador de Compras'
        ][$tipo] ?? 'Usuario';
        
        // Eliminar el usuario
        $usuario->delete();
        
        // Mensaje de éxito
        $mensaje = "Usuario <strong>{$nombreCompleto}</strong> ({$tipoUsuario}) ";
        $mensaje .= "con email <strong>{$email}</strong> eliminado correctamente.";
        
        return redirect()->route('administradores.index')
            ->with('success', $mensaje);
            
    } catch (\Illuminate\Database\QueryException $e) {
        // Error de integridad referencial (foreign key constraints)
        $errorCode = $e->errorInfo[1];
        
        if ($errorCode == 1451) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar este usuario porque tiene registros asociados en el sistema.');
        }
        
        return redirect()->back()
            ->with('error', 'Error de base de datos al eliminar el usuario: ' . $e->getMessage());
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
    }
}
}
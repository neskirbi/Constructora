<?php

namespace App\Http\Controllers\Aingresos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\Models\Contrato;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Obtener parámetros de búsqueda
        $search = $request->input('search');
        
        // Construir consulta base
        $query = Contrato::query();
        
        // Aplicar búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('obra', 'like', "%{$search}%")
                  ->orWhere('contrato_no', 'like', "%{$search}%")
                  ->orWhere('cliente', 'like', "%{$search}%")
                  ->orWhere('lugar', 'like', "%{$search}%")
                  ->orWhere('empresa', 'like', "%{$search}%");
            });
        }
        
      
        
        // Obtener los contratos con paginación (15 por página)
        $contratos = $query->orderBy('created_at', 'desc')->paginate();
        
        return view('aingresos.contratos.index', compact('contratos'));
    }

    function create(){
        return view('aingresos.contratos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            // Validar todos los campos del formulario según la estructura de la tabla
            $validatedData = $request->validate([
                // Información general
                'obra' => 'nullable|string',
                'empresa' => 'nullable|string|max:255',
                'contrato_no' => 'required|string|max:255|unique:contratos',
                'frente' => 'nullable|string|max:255',
                'gerencia' => 'nullable|string|max:255',
                'cliente' => 'nullable|string|max:255',
                'lugar' => 'nullable|string|max:255',
                
                // Montos del contrato (nueva estructura)
                'concepto' => 'nullable|string|max:50',
                'subtotal' => 'nullable|numeric|min:0',
                'iva' => 'nullable|numeric|min:0',
                'total' => 'nullable|numeric|min:0',
                'monto_anticipo' => 'nullable|numeric|min:0',
                
                // Duración y fechas
                'duracion' => 'nullable|string|max:255',
                'fecha_contrato' => 'nullable|date',
                'fecha_inicio_obra' => 'nullable|date',
                'fecha_terminacion_obra' => 'nullable|date',
                
                // Observaciones
                'observaciones' => 'nullable|string',
                
                // Datos fiscales
                'razon_social' => 'nullable|string|max:255',
                'rfc' => 'nullable|string|max:255',
                
                // Dirección (nueva estructura)
                'calle_numero' => 'nullable|string|max:255',
                'colonia' => 'nullable|string|max:255',
                'codigo_postal' => 'nullable|string|max:255',
                'entidad' => 'nullable|string|max:255',
                'alcaldia_municipio' => 'nullable|string|max:255',
                
                // Contacto
                'telefono' => 'nullable|string|max:255',
                
                // Coordenadas
                'latitud' => 'nullable|numeric',
                'longitud' => 'nullable|numeric',
                
                // Datos bancarios (nueva estructura)
                'banco' => 'nullable|string|max:255',
                'no_cuenta' => 'nullable|string|max:255',
                'sucursal' => 'nullable|string|max:255',
                'clabe_interbancaria' => 'nullable|string|max:255',
                
                // Correo para facturas
                'mail_facturas' => 'nullable|email|max:255',
                
                // Representante legal
                'representante_legal' => 'nullable|string|max:255',
            ], [
                'contrato_no.required' => 'El número de contrato es obligatorio',
                'contrato_no.unique' => 'Este número de contrato ya existe',
                'mail_facturas.email' => 'El correo electrónico no es válido',
            ]);

            // Preparar todos los datos para insertar
            $datosContrato = [
                'id' => GetUuid(),
                'id_usuario' => GetId(),
                'contrato_no' => $validatedData['contrato_no'],
                
                // Campos de la tabla - usando null si no están presentes
                'obra' => $validatedData['obra'] ?? null,
                'empresa' => $validatedData['empresa'] ?? null,
                'frente' => $validatedData['frente'] ?? null,
                'gerencia' => $validatedData['gerencia'] ?? null,
                'cliente' => $validatedData['cliente'] ?? null,
                'lugar' => $validatedData['lugar'] ?? null,
                
                // Montos del contrato (nueva estructura)
                'concepto' => $validatedData['concepto'] ?? null,
                'subtotal' => $validatedData['subtotal'] ?? null,
                'iva' => $validatedData['iva'] ?? null,
                'total' => $validatedData['total'] ?? null,
                'monto_anticipo' => $validatedData['monto_anticipo'] ?? null,
                
                // Duración y fechas
                'duracion' => $validatedData['duracion'] ?? null,
                'fecha_contrato' => $validatedData['fecha_contrato'] ?? null,
                'fecha_inicio_obra' => $validatedData['fecha_inicio_obra'] ?? null,
                'fecha_terminacion_obra' => $validatedData['fecha_terminacion_obra'] ?? null,
                
                // Observaciones
                'observaciones' => $validatedData['observaciones'] ?? null,
                
                // Datos fiscales
                'razon_social' => $validatedData['razon_social'] ?? null,
                'rfc' => $validatedData['rfc'] ?? null,
                
                // Dirección (nueva estructura)
                'calle_numero' => $validatedData['calle_numero'] ?? null,
                'colonia' => $validatedData['colonia'] ?? null,
                'codigo_postal' => $validatedData['codigo_postal'] ?? null,
                'entidad' => $validatedData['entidad'] ?? null,
                'alcaldia_municipio' => $validatedData['alcaldia_municipio'] ?? null,
                
                // Contacto
                'telefono' => $validatedData['telefono'] ?? null,
                
                // Coordenadas
                'latitud' => $validatedData['latitud'] ?? null,
                'longitud' => $validatedData['longitud'] ?? null,
                
                // Bancarios (nueva estructura)
                'banco' => $validatedData['banco'] ?? null,
                'no_cuenta' => $validatedData['no_cuenta'] ?? null,
                'sucursal' => $validatedData['sucursal'] ?? null,
                'clabe_interbancaria' => $validatedData['clabe_interbancaria'] ?? null,
                
                // Correo
                'mail_facturas' => $validatedData['mail_facturas'] ?? null,
                
                // Representante
                'representante_legal' => $validatedData['representante_legal'] ?? null,
                
                // Timestamps
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Crear el contrato en la base de datos
            $contrato = Contrato::create($datosContrato);

            // Redireccionar con mensaje de éxito
            return redirect()->route('contratos.index')
                ->with('success', 'Contrato ' . $contrato->contrato_no . ' creado exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores de validación
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            // Para cualquier otro error
            \Log::error('Error al crear contrato: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar el contrato: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Buscar el contrato
        $contrato = Contrato::findOrFail($id);
        
        // Cargar la vista de edición (reutilizamos el formulario CREATE)
        return view('aingresos.contratos.show', compact('contrato'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $contrato = Contrato::findOrFail($id);

            // Validar todos los campos del formulario según la estructura de la tabla
            $validatedData = $request->validate([
                // Información general
                'obra' => 'nullable|string',
                'empresa' => 'nullable|string|max:255',
                'contrato_no' => 'required|string|max:255|unique:contratos,contrato_no,' . $id . ',id',
                'frente' => 'nullable|string|max:255',
                'gerencia' => 'nullable|string|max:255',
                'cliente' => 'nullable|string|max:255',
                'lugar' => 'nullable|string|max:255',
                
                // Montos del contrato (nueva estructura)
                'concepto' => 'nullable|string|max:50',
                'subtotal' => 'nullable|numeric|min:0',
                'iva' => 'nullable|numeric|min:0',
                'total' => 'nullable|numeric|min:0',
                'monto_anticipo' => 'nullable|numeric|min:0',
                
                // Duración y fechas
                'duracion' => 'nullable|string|max:255',
                'fecha_contrato' => 'nullable|date',
                'fecha_inicio_obra' => 'nullable|date',
                'fecha_terminacion_obra' => 'nullable|date',
                
                // Observaciones
                'observaciones' => 'nullable|string',
                
                // Datos fiscales
                'razon_social' => 'nullable|string|max:255',
                'rfc' => 'nullable|string|max:255',
                
                // Dirección (nueva estructura)
                'calle_numero' => 'nullable|string|max:255',
                'colonia' => 'nullable|string|max:255',
                'codigo_postal' => 'nullable|string|max:255',
                'entidad' => 'nullable|string|max:255',
                'alcaldia_municipio' => 'nullable|string|max:255',
                
                // Contacto
                'telefono' => 'nullable|string|max:255',
                
                // Coordenadas
                'latitud' => 'nullable|numeric',
                'longitud' => 'nullable|numeric',
                
                // Datos bancarios (nueva estructura)
                'banco' => 'nullable|string|max:255',
                'no_cuenta' => 'nullable|string|max:255',
                'sucursal' => 'nullable|string|max:255',
                'clabe_interbancaria' => 'nullable|string|max:255',
                
                // Correo para facturas
                'mail_facturas' => 'nullable|email|max:255',
                
                // Representante legal
                'representante_legal' => 'nullable|string|max:255',
            ], [
                'contrato_no.required' => 'El número de contrato es obligatorio',
                'contrato_no.unique' => 'Este número de contrato ya existe',
                'mail_facturas.email' => 'El correo electrónico no es válido',
            ]);

            // Preparar todos los datos para actualizar
            $datosContrato = [
                'obra' => $validatedData['obra'] ?? null,
                'empresa' => $validatedData['empresa'] ?? null,
                'contrato_no' => $validatedData['contrato_no'],
                'frente' => $validatedData['frente'] ?? null,
                'gerencia' => $validatedData['gerencia'] ?? null,
                'cliente' => $validatedData['cliente'] ?? null,
                'lugar' => $validatedData['lugar'] ?? null,
                
                // Montos del contrato (nueva estructura)
                'concepto' => $validatedData['concepto'] ?? null,
                'subtotal' => $validatedData['subtotal'] ?? null,
                'iva' => $validatedData['iva'] ?? null,
                'total' => $validatedData['total'] ?? null,
                'monto_anticipo' => $validatedData['monto_anticipo'] ?? null,
                
                // Duración y fechas
                'duracion' => $validatedData['duracion'] ?? null,
                'fecha_contrato' => $validatedData['fecha_contrato'] ?? null,
                'fecha_inicio_obra' => $validatedData['fecha_inicio_obra'] ?? null,
                'fecha_terminacion_obra' => $validatedData['fecha_terminacion_obra'] ?? null,
                
                // Observaciones
                'observaciones' => $validatedData['observaciones'] ?? null,
                
                // Datos fiscales
                'razon_social' => $validatedData['razon_social'] ?? null,
                'rfc' => $validatedData['rfc'] ?? null,
                
                // Dirección (nueva estructura)
                'calle_numero' => $validatedData['calle_numero'] ?? null,
                'colonia' => $validatedData['colonia'] ?? null,
                'codigo_postal' => $validatedData['codigo_postal'] ?? null,
                'entidad' => $validatedData['entidad'] ?? null,
                'alcaldia_municipio' => $validatedData['alcaldia_municipio'] ?? null,
                
                // Contacto
                'telefono' => $validatedData['telefono'] ?? null,
                
                // Coordenadas
                'latitud' => $validatedData['latitud'] ?? null,
                'longitud' => $validatedData['longitud'] ?? null,
                
                // Bancarios (nueva estructura)
                'banco' => $validatedData['banco'] ?? null,
                'no_cuenta' => $validatedData['no_cuenta'] ?? null,
                'sucursal' => $validatedData['sucursal'] ?? null,
                'clabe_interbancaria' => $validatedData['clabe_interbancaria'] ?? null,
                
                // Correo
                'mail_facturas' => $validatedData['mail_facturas'] ?? null,
                
                // Representante
                'representante_legal' => $validatedData['representante_legal'] ?? null,
                
                // Actualizar timestamp
                'updated_at' => now(),
            ];

            // Actualizar el contrato en la base de datos
            $contrato->update($datosContrato);

            // Redireccionar con mensaje de éxito
            return redirect()->back()
                ->with('success', 'Contrato ' . $contrato->contrato_no . ' actualizado exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores de validación
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            // Para cualquier otro error
            \Log::error('Error al actualizar contrato: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el contrato: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Buscar el contrato
            $contrato = Contrato::findOrFail($id);
            
            // Verificar si existen registros en la tabla ingresos relacionados con este contrato
            $tieneIngresos = DB::table('ingresos')
                ->where('id_contrato', $id)
                ->exists();
            
            if ($tieneIngresos) {
                // Si hay ingresos relacionados, no se puede eliminar
                return redirect()->route('contratos.index')
                    ->with('error', 'No se puede eliminar el contrato "' . $contrato->contrato_no . 
                        '" porque tiene registros de ingresos asociados. ' .
                        'Elimine primero los ingresos relacionados.');
            }
            
            // Guardar información para el mensaje
            $contratoNo = $contrato->contrato_no;
            $obraNombre = $contrato->obra;
            
            // Eliminar el contrato
            $contrato->delete();
            
            // Redireccionar con mensaje de éxito
            return redirect()->route('contratos.index')
                ->with('success', 'Contrato "' . $contratoNo . '" - "' . $obraNombre . '" eliminado exitosamente.');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Si no se encuentra el contrato
            return redirect()->route('contratos.index')
                ->with('error', 'El contrato no existe o ya fue eliminado.');
                
        } catch (\Exception $e) {
            // Para cualquier otro error
            \Log::error('Error al eliminar contrato ID ' . $id . ': ' . $e->getMessage());
            
            return redirect()->route('contratos.index')
                ->with('error', 'Error al eliminar el contrato: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Aingresos;

use App\Http\Controllers\Controller;
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
        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            // Validar todos los campos del formulario
            $validatedData = $request->validate([
                // Información general
                'contrato_no' => 'required|string|max:255|unique:contratos',
                'obra' => 'required|string',
                'obras' => 'nullable|string|max:255',
                'contrato_fecha' => 'required|date',
                'duracion' => 'nullable|string|max:255',
                
                // Información de la empresa
                'empresa' => 'nullable|string|max:255',
                'frente' => 'nullable|string|max:255',
                'gerencia' => 'nullable|string|max:255',
                
                // Datos del cliente
                'cliente' => 'required|string|max:255',
                'lugar' => 'required|string|max:255',
                
                // Montos principales
                'importe' => 'nullable|numeric|min:0',
                'iva' => 'nullable|numeric|min:0',
                'total' => 'nullable|numeric|min:0',
                
                // Montos de convenio
                'importe_convenio' => 'nullable|numeric|min:0',
                'iva_convenio' => 'nullable|numeric|min:0',
                'total_convenio' => 'nullable|numeric|min:0',
                
                // Montos totales
                'importe_total' => 'required|numeric|min:0',
                'iva_total' => 'nullable|numeric|min:0',
                'total_total' => 'nullable|numeric|min:0',
                
                // Anticipo
                'anticipo' => 'nullable|numeric|min:0',
                
                // Fechas de obra
                'inicio_de_obra' => 'nullable|date',
                'terminacion_de_obra' => 'nullable|date',
                
                // Observaciones
                'observaciones' => 'nullable|string',
                
                // Datos fiscales del cliente
                'razon_social' => 'nullable|string|max:255',
                'rfc' => 'nullable|string|max:20',
                
                // Dirección del cliente
                'calle_y_numero' => 'nullable|string|max:255',
                'colonia' => 'nullable|string|max:255',
                'codigo_postal' => 'nullable|string|max:10',
                'delegacion' => 'nullable|string|max:255',
                'municipio' => 'nullable|string|max:255',
                
                // Contacto
                'telefono' => 'nullable|string|max:255',
                
                // Datos bancarios
                'banco' => 'nullable|string|max:255',
                'n_cuenta' => 'nullable|string|max:255',
                'sucursal' => 'nullable|string|max:255',
                'clabe_interbancaria' => 'nullable|string|max:255',
                
                // Correo para facturas
                'mail_facturas' => 'nullable|email|max:255',
                
                // Representante legal
                'representante_legal' => 'nullable|string|max:255',
            ], [
                'contrato_no.required' => 'El número de contrato es obligatorio',
                'contrato_no.unique' => 'Este número de contrato ya existe',
                'obra.required' => 'El nombre de la obra es obligatorio',
                'cliente.required' => 'El nombre del cliente es obligatorio',
                'lugar.required' => 'La ubicación es obligatoria',
                'importe_total.required' => 'El importe total es obligatorio',
                'importe_total.numeric' => 'El importe total debe ser un número',
                'contrato_fecha.required' => 'La fecha del contrato es obligatoria',
                'mail_facturas.email' => 'El correo electrónico no es válido',
            ]);

            // Generar ID único con GetUuid()
            $id = GetUuid();
            
            // Preparar todos los datos para insertar
            $datosContrato = [
                'id' => $id,
                'contrato_no' => $validatedData['contrato_no'],
                'obra' => $validatedData['obra'],
                'cliente' => $validatedData['cliente'],
                'lugar' => $validatedData['lugar'],
                'importe_total' => $validatedData['importe_total'],
                'contrato_fecha' => $validatedData['contrato_fecha'],
                
                // Campos opcionales - usar null si están vacíos
                'obras' => $validatedData['obras'] ?? null,
                'empresa' => $validatedData['empresa'] ?? null,
                'frente' => $validatedData['frente'] ?? null,
                'gerencia' => $validatedData['gerencia'] ?? null,
                'duracion' => $validatedData['duracion'] ?? null,
                
                // Montos
                'importe' => $validatedData['importe'] ?? null,
                'iva' => $validatedData['iva'] ?? null,
                'total' => $validatedData['total'] ?? null,
                'importe_convenio' => $validatedData['importe_convenio'] ?? null,
                'iva_convenio' => $validatedData['iva_convenio'] ?? null,
                'total_convenio' => $validatedData['total_convenio'] ?? null,
                'iva_total' => $validatedData['iva_total'] ?? null,
                'total_total' => $validatedData['total_total'] ?? null,
                'anticipo' => $validatedData['anticipo'] ?? null,
                
                // Fechas
                'inicio_de_obra' => $validatedData['inicio_de_obra'] ?? null,
                'terminacion_de_obra' => $validatedData['terminacion_de_obra'] ?? null,
                
                // Observaciones
                'observaciones' => $validatedData['observaciones'] ?? null,
                
                // Datos fiscales
                'razon_social' => $validatedData['razon_social'] ?? null,
                'rfc' => $validatedData['rfc'] ?? null,
                
                // Dirección
                'calle_y_numero' => $validatedData['calle_y_numero'] ?? null,
                'colonia' => $validatedData['colonia'] ?? null,
                'codigo_postal' => $validatedData['codigo_postal'] ?? null,
                'delegacion' => $validatedData['delegacion'] ?? null,
                'municipio' => $validatedData['municipio'] ?? null,
                
                // Contacto
                'telefono' => $validatedData['telefono'] ?? null,
                
                // Bancarios
                'banco' => $validatedData['banco'] ?? null,
                'n_cuenta' => $validatedData['n_cuenta'] ?? null,
                'sucursal' => $validatedData['sucursal'] ?? null,
                'clabe_interbancaria' => $validatedData['clabe_interbancaria'] ?? null,
                
                // Correo
                'mail_facturas' => $validatedData['mail_facturas'] ?? null,
                
                // Representante
                'representante_legal' => $validatedData['representante_legal'] ?? null,
            ];

            // Calcular totales si faltan
            if (empty($datosContrato['total']) && !empty($datosContrato['importe']) && !empty($datosContrato['iva'])) {
                $datosContrato['total'] = $datosContrato['importe'] + $datosContrato['iva'];
            }
            
            if (empty($datosContrato['total_convenio']) && !empty($datosContrato['importe_convenio']) && !empty($datosContrato['iva_convenio'])) {
                $datosContrato['total_convenio'] = $datosContrato['importe_convenio'] + $datosContrato['iva_convenio'];
            }
            
            if (empty($datosContrato['total_total']) && !empty($datosContrato['importe_total']) && !empty($datosContrato['iva_total'])) {
                $datosContrato['total_total'] = $datosContrato['importe_total'] + $datosContrato['iva_total'];
            }

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
        try {
            // Buscar el contrato
            $contrato = Contrato::findOrFail($id);
            
            // Cargar la vista de edición (reutilizamos el formulario CREATE)
            return view('aingresos.contratos.show', compact('contrato'));
            
        } catch (\Exception $e) {
            // Si no se encuentra el contrato
            return redirect()->route('contratos.index')
                ->with('error', 'Contrato no encontrado: ' . $e->getMessage());
        }
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
            // Buscar el contrato
            $contrato = Contrato::findOrFail($id);
            
            // Validar los datos (misma validación que store pero ignorando el contrato actual)
            $validated = $request->validate([
                // Información general
                'contrato_no' => 'required|string|max:255|unique:contratos,contrato_no,' . $id . ',id',
                'contrato_fecha' => 'required|date',
                'obra' => 'required|string',
                'obras' => 'nullable|string|max:255',
                'duracion' => 'nullable|string|max:255',
                
                // Datos del cliente
                'cliente' => 'required|string|max:255',
                'empresa' => 'nullable|string|max:255',
                'razon_social' => 'nullable|string|max:255',
                'rfc' => 'nullable|string|max:20',
                'representante_legal' => 'nullable|string|max:255',
                
                // Ubicación y fechas
                'lugar' => 'required|string|max:255',
                'inicio_de_obra' => 'nullable|date',
                'terminacion_de_obra' => 'nullable|date',
                'frente' => 'nullable|string|max:255',
                'gerencia' => 'nullable|string|max:255',
                
                // Montos económicos
                'importe' => 'nullable|numeric|min:0',
                'iva' => 'nullable|numeric|min:0',
                'total' => 'nullable|numeric|min:0',
                'importe_convenio' => 'nullable|numeric|min:0',
                'iva_convenio' => 'nullable|numeric|min:0',
                'total_convenio' => 'nullable|numeric|min:0',
                'importe_total' => 'required|numeric|min:0',
                'iva_total' => 'nullable|numeric|min:0',
                'total_total' => 'nullable|numeric|min:0',
                'anticipo' => 'nullable|numeric|min:0',
                
                // Información adicional
                'observaciones' => 'nullable|string',
                'telefono' => 'nullable|string|max:255',
                'mail_facturas' => 'nullable|email|max:255',
                
                // Información bancaria (opcional)
                'banco' => 'nullable|string|max:255',
                'n_cuenta' => 'nullable|string|max:255',
                'sucursal' => 'nullable|string|max:255',
                'clabe_interbancaria' => 'nullable|string|max:255',
                
                // Dirección (opcional)
                'calle_y_numero' => 'nullable|string|max:255',
                'colonia' => 'nullable|string|max:255',
                'codigo_postal' => 'nullable|string|max:10',
                'delegacion' => 'nullable|string|max:255',
                'municipio' => 'nullable|string|max:255',
            ], [
                // Mensajes personalizados de error
                'contrato_no.required' => 'El número de contrato es obligatorio.',
                'contrato_no.unique' => 'Este número de contrato ya está registrado.',
                'contrato_fecha.required' => 'La fecha del contrato es obligatoria.',
                'obra.required' => 'El nombre de la obra es obligatorio.',
                'cliente.required' => 'El nombre del cliente es obligatorio.',
                'lugar.required' => 'La ubicación es obligatoria.',
                'importe_total.required' => 'El importe total es obligatorio.',
                'importe_total.numeric' => 'El importe total debe ser un número.',
                'importe_total.min' => 'El importe total no puede ser negativo.',
                'mail_facturas.email' => 'El correo electrónico no tiene un formato válido.',
            ]);

            // Calcular campos si no se proporcionaron
            if (!$request->filled('total') && $request->filled('importe') && $request->filled('iva')) {
                $validated['total'] = $request->importe + $request->iva;
            }
            
            if (!$request->filled('total_convenio') && $request->filled('importe_convenio') && $request->filled('iva_convenio')) {
                $validated['total_convenio'] = $request->importe_convenio + $request->iva_convenio;
            }
            
            if (!$request->filled('total_total') && $request->filled('importe_total') && $request->filled('iva_total')) {
                $validated['total_total'] = $request->importe_total + $request->iva_total;
            }

            // Actualizar el contrato
            $contrato->update($validated);
            
            // Redireccionar con mensaje de éxito
            return redirect('contratos'.'/'.$contrato->id)
                ->with('success', 'Contrato "' . $contrato->contrato_no . '" actualizado exitosamente.');
                
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
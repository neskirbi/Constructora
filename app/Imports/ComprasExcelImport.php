<?php

namespace App\Imports;

use App\Models\CarritoCompra;
use App\Models\ProductoServicio;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToArray;

class ComprasExcelImport implements ToArray
{
    protected $sessionId;
    protected $contratoId;
    protected $itemsAgregados = 0;
    protected $itemsErroneos = 0;
    protected $mensaje = '';
    protected $errores = [];

    public function __construct($sessionId, $contratoId = null)
    {
        $this->sessionId = $sessionId;
        $this->contratoId = $contratoId;
    }

    public function array(array $rows)
    {
        // La Hoja1 tiene el formato simple:
        // Columna A = Clave
        // Columna B = Descripción
        // Columna C = Unidad
        // Columna D = Cantidad
        // Columna E = Link
        // Columna F = Observaciones
        
        // La fila 1 es el encabezado, los datos empiezan en la fila 2 (índice 1)
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $filaReal = $i + 1;
            
            $clave = trim($row[0] ?? '');      // Columna A: Clave
            $descripcion = trim($row[1] ?? ''); // Columna B: Descripción
            $unidad = trim($row[2] ?? '');      // Columna C: Unidad
            $cantidad = floatval(str_replace(',', '', $row[3] ?? 0)); // Columna D: Cantidad
            $link = trim($row[4] ?? '');        // Columna E: Link
            $observaciones = trim($row[5] ?? ''); // Columna F: Observaciones

            // Saltar filas vacías
            if (empty($clave) && empty($descripcion)) {
                continue;
            }

            try {
                if (empty($clave) || empty($descripcion) || empty($unidad) || $cantidad <= 0) {
                    $this->itemsErroneos++;
                    $this->errores[] = "Fila $filaReal: Datos incompletos (Clave: $clave, Cantidad: $cantidad)";
                    continue;
                }

                $precio = 0;
                $catalogo = ProductoServicio::where('clave', $clave)->first();
                if ($catalogo) {
                    $precio = $catalogo->ult_costo ?? 0;
                }

                $item = CarritoCompra::create([
                    'session_id' => $this->sessionId,
                    'contrato_id' => $this->contratoId,
                    'clave' => $clave,
                    'descripcion' => $descripcion,
                    'unidad' => $unidad,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'observaciones' => $observaciones,
                    'link' => $link,
                    'fila_excel' => $filaReal,
                ]);

                if ($precio > 0) {
                    $item->subtotal = $cantidad * $precio;
                    $item->iva = $item->subtotal * 0.16;
                    $item->total = $item->subtotal + $item->iva;
                    $item->save();
                }

                $this->itemsAgregados++;

            } catch (\Exception $e) {
                $this->itemsErroneos++;
                $this->errores[] = "Fila $filaReal: " . $e->getMessage();
                Log::error('Error importando fila ' . $filaReal . ': ' . $e->getMessage());
            }
        }

        $this->mensaje = "Se procesaron " . $this->itemsAgregados . " items correctamente.";
        if ($this->itemsErroneos > 0) {
            $this->mensaje .= " {$this->itemsErroneos} items con errores.";
        }
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function getItemsAgregados()
    {
        return $this->itemsAgregados;
    }

    public function getItemsErroneos()
    {
        return $this->itemsErroneos;
    }

    public function getErrores()
    {
        return $this->errores;
    }
}
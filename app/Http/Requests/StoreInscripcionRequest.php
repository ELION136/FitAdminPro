<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInscripcionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'idCliente' => 'required|exists:clientes,idCliente',
            'productos' => 'required|array',
            'productos.*.tipoProducto' => 'required|in:membresia,servicio',
            'productos.*.idProducto' => 'required|integer',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.descuento' => 'nullable|numeric|min:0',
            'productos.*.tipoDescuento' => 'nullable|in:promocion,paquete,ninguno',
            'estadoPago' => 'required|in:pendiente,completo,parcial',
            'montoPagado' => 'required|numeric|min:0',
            'totalPago' => 'required|numeric|min:0',
            'descuentoTotal' => 'nullable|numeric|min:0',
        ];
    }
    public function messages()
    {
        return [
            'idCliente.required' => 'Debe seleccionar un cliente.',
            'idCliente.exists' => 'El cliente seleccionado no existe.',
            'productos.required' => 'Debe seleccionar al menos un producto.',
            // Otros mensajes personalizados...
        ];
    }
    protected function prepareForValidation()
    {
        $productos = json_decode($this->productos, true);

        // Verificar si la decodificación fue exitosa
        if (json_last_error() !== JSON_ERROR_NONE) {
            $productos = [];
        }

        $this->merge([
            'productos' => $productos,
            'descuentoTotal' => $this->input('descuentoTotal', 0.00),
        ]);
    }
}

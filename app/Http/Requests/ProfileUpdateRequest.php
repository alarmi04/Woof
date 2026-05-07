<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:150',
                Rule::unique(Usuario::class, 'Correo')->ignore($this->user()->idUsuario, 'idUsuario'),
            ],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'tipo_vivienda' => ['nullable', 'string', 'in:piso,casa_sin_jardin,casa_con_jardin'],
            'numero_hijos' => ['nullable', 'integer', 'min:0'],
            'nivel_actividad' => ['nullable', 'integer', 'in:1,2,3'],
            'experiencia_mascotas' => ['nullable', 'integer', 'in:0,1,2'],
            'tiempo_disponible' => ['nullable', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ];
    }
}

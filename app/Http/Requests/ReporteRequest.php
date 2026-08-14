<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReporteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador() ?? false;
    }

    public function rules(): array
    {
        return [
            'fecha_desde' => [
                'nullable',
                'date',
                'before_or_equal:fecha_hasta',
            ],

            'fecha_hasta' => [
                'nullable',
                'date',
                'after_or_equal:fecha_desde',
                'before_or_equal:today',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_desde.date' =>
                'La fecha inicial no es válida.',

            'fecha_desde.before_or_equal' =>
                'La fecha inicial no puede ser posterior a la fecha final.',

            'fecha_hasta.date' =>
                'La fecha final no es válida.',

            'fecha_hasta.after_or_equal' =>
                'La fecha final no puede ser anterior a la fecha inicial.',

            'fecha_hasta.before_or_equal' =>
                'La fecha final no puede ser posterior al día de hoy.',
        ];
    }
}
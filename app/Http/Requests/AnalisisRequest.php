<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AnalisisRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'indikator_id' => 'required|exists:indikators,id',
            'triwulan' => 'required|integer|min:1|max:4',
            'narasi_analisis' => 'nullable',
            'kendala' => 'nullable',
            'solusi' => 'nullable',
            'rencana_tindak_lanjut' => 'nullable',
            'penjelasan_lainnya' => 'nullable',
            'pic_tindak_lanjut' => 'nullable',
            'batas_waktu' => 'nullable|date',
            'link_bukti_kinerja' => 'nullable|url',
            'link_bukti_tindak_lanjut' => 'nullable|url',
            'file_bukti_kinerja' => 'nullable|file|mimes:pdf,jpg,png,zip,doc,docx|max:10240',
            'file_bukti_tindak_lanjut' => 'nullable|file|mimes:pdf,jpg,png,zip,doc,docx|max:10240',
        ];
    }
}

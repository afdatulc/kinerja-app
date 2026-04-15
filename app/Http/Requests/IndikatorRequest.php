<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndikatorRequest extends FormRequest
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
            'tujuan' => 'required',
            'sasaran' => 'required',
            'indikator_kinerja' => 'required',
            'jenis_indikator' => 'required|in:IKU,Proksi',
            'periode' => 'required|in:Triwulanan,Tahunan',
            'tipe' => 'required|in:Persen,Non Persen',
            'satuan' => 'required',
            'target_tahunan' => 'required|numeric',
            'tahun' => 'required|integer',
            'pic_id' => 'nullable|exists:pegawais,id',
            'dasar_hitung' => 'nullable|string',
            'link_bukti_kinerja' => 'nullable|url',
            'link_bukti_tindak_lanjut' => 'nullable|url',
            'penjelasan_lainnya'=> 'nullable|string',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdministrator();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'land_area' => 'required|numeric|min:0',
            'building_area' => 'required|numeric|min:0',
            'status' => 'required|in:available,sold,occupied',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'handover_date' => 'nullable|date',
            'price' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'block_unit' => 'required|string|max:50|unique:houses,block_unit',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'address.required' => 'Alamat rumah wajib diisi.',
            'type.required' => 'Tipe rumah wajib diisi.',
            'land_area.required' => 'Luas tanah wajib diisi.',
            'building_area.required' => 'Luas bangunan wajib diisi.',
            'price.required' => 'Harga jual wajib diisi.',
            'bedrooms.required' => 'Jumlah kamar tidur wajib diisi.',
            'bathrooms.required' => 'Jumlah kamar mandi wajib diisi.',
            'block_unit.required' => 'Nomor blok/unit wajib diisi.',
            'block_unit.unique' => 'Nomor blok/unit sudah digunakan.',
        ];
    }
}
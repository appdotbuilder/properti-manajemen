<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user->isAdministrator() || $user->isHousingManager();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'house_id' => 'required|exists:houses,id',
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'move_in_date' => 'nullable|date',
            'move_out_date' => 'nullable|date|after:move_in_date',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
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
            'house_id.required' => 'Rumah harus dipilih.',
            'house_id.exists' => 'Rumah yang dipilih tidak valid.',
            'name.required' => 'Nama penghuni wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'move_out_date.after' => 'Tanggal pindah keluar harus setelah tanggal pindah masuk.',
        ];
    }
}
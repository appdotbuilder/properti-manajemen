<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !$this->user()->isResident();
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
            'resident_id' => 'nullable|exists:residents,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|max:100',
            'status' => 'required|in:pending,paid,overdue,cancelled',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
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
            'payment_date.required' => 'Tanggal pembayaran wajib diisi.',
            'amount.required' => 'Jumlah pembayaran wajib diisi.',
            'amount.min' => 'Jumlah pembayaran tidak boleh negatif.',
            'type.required' => 'Jenis pembayaran wajib diisi.',
            'due_date.required' => 'Tanggal jatuh tempo wajib diisi.',
        ];
    }
}
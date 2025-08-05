<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $complaint = $this->route('complaint');

        // Residents can only edit their own complaints if they're still 'new'
        if ($user->isResident()) {
            return $complaint->resident->user_id === $user->id && $complaint->status === 'new';
        }

        // Staff can edit any complaint
        return $user->isAdministrator() || $user->isHousingManager();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        if ($user->isResident()) {
            // Residents can only update basic complaint info
            return [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'priority' => 'required|in:low,medium,high,urgent',
                'category' => 'nullable|string|max:100',
                'attachments' => 'nullable|array',
                'attachments.*' => 'string|max:255',
            ];
        }

        // Staff can update all fields including status and response
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:new,in_progress,completed,rejected',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'nullable|string|max:100',
            'assigned_to' => 'nullable|exists:users,id',
            'response' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'string|max:255',
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
            'title.required' => 'Judul keluhan wajib diisi.',
            'description.required' => 'Deskripsi keluhan wajib diisi.',
            'status.required' => 'Status keluhan wajib dipilih.',
            'priority.required' => 'Prioritas keluhan wajib dipilih.',
            'assigned_to.exists' => 'Staff yang ditugaskan tidak valid.',
        ];
    }
}
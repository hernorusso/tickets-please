<?php

namespace App\Http\Requests\Api\v1;

class UpdateTicketRequest extends BaseTicketRequest
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
            'data.attributes.title' => 'sometimes|string',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|in:A,C,H,X',
            'data.attributes.createdAt' => 'sometimes|date_format:Y-m-d\TH:i:s.u\Z',
            'data.attributes.updatedAt' => 'sometimes|date_format:Y-m-d\TH:i:s.u\Z',
            'data.relationships.author.data.id' => 'sometimes|integer',
        ];
    }
}
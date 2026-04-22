<?php

namespace App\Http\Requests\Api\v1;

use App\Permisions\V1\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends BaseTicketRequest
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
        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|in:A,C,H,X',
            'data.relationships.author.data.id' => 'required|integer|exists:users,id',
        ];

        if ($this->routeIs('tickets.store')) {
            $user = $this->user();
            if ($user->tokenCan(Abilities::CreateOwnTicket)) {
                $rules['data.relationships.author.data.id'] .= '|size:' . $user->id;
            }
        }
        
        return $rules;
    }
}
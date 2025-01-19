<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array{flight_number: string, departure_time: string, source: string, destination_airport: string, passport_id: string}
     */
    public function rules(): array
    {
        return [
            'flight_number' => 'required|string|max:10',
            'departure_time' => 'required|date|after:now',
            'source' => 'required|string',
            'destination_airport' => 'required|string|different:source',
            'passport_id' => 'required|string|size:9',
        ];
    }
}

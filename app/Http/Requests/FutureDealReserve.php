<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FutureDealReserve extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'date' => 'required',
            'time' => 'required',
            'persons' => 'required|min:1',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'name' => 'required',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'U bent vergeten om een naam in te vullen.',
            'email.required' => 'U bent vergeten om een e-mailadres in te vullen.',
            'email.email' => 'Uw opgegeven e-mailadres is ongeldig.',
            'phone.required' => 'U bent vergeten om een telefoonnummer in te vullen.',
            'phone.min' => 'Uw telefoonnummer is te kort (minimaal 10 cijfers).',
            'date.required' => 'U bent vergeten om een datum in te vullen.',
            'time.required' => 'U bent vergeten om een tijd in te vullen.',
            'persons.required' => 'U bent vergeten om een personen in te vullen.',            
        ];
    }

}

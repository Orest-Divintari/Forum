<?php

namespace App\Http\Requests;

use App\Rules\Recaptcha;
use App\Rules\SpamFree;
use Illuminate\Foundation\Http\FormRequest;

class ThreadCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Recaptcha $recaptcha)
    {
        return [
            'channel_id' => 'exists:channels,id',
            'channel_id' => 'required',
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
            'g-recaptcha-response' => ['required', $recaptcha],
        ];
    }

}
<?php

namespace App\Http\Requests;

use App\Rules\SpamFree;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ThreadUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->route('thread'));

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'channel_id' => 'exists:channels,id',
            'channel_id' => 'required',
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
        ];
    }
}
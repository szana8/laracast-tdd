<?php

namespace App\Http\Requests;

use App\Reply;
use App\Rules\SpamFree;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\ThrottleException;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new Reply());
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException('You are replying frequently.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => ['required', new SpamFree()]
        ];
    }
}

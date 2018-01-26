<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Reply;

class CreateReplyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Gate::allows('create', new Reply);
    }

    /**
     * override parent failedAuthorization()
     * which will throw UnAuthorizedException
     */
    protected function failedAuthorization()
    {
        throw new \App\Exceptions\ThrottleException();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|spamfree'
        ];
    }
}

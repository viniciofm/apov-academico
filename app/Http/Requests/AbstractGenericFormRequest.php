<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;

/**
 *
 */
abstract class AbstractGenericFormRequest extends Request
{
    /**
     * @return bool
     */
    public function authorize() : bool
    {
        /** @todo If is development|production, send request when user authenticated */
        /** @todo If local, this validation is not necessary */

        return true;
    }
}

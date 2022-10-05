<?php


namespace Modules\User\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class PasswordRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'sometimes|required|uuid|exists:users,id',
            'password' => 'required|string',
            'confirm_password' => 'required|string|same:password',
        ];
    }

    protected function formatItems()
    {

    }

    public function getValidatorInstance()
    {
        $this->formatItems();

        return parent::getValidatorInstance();
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'id' => 'ID',
            'password' => 'Senha',
            'confirm_password' => 'Confirmação de Senha',
        ];
    }
}

<?php


namespace Modules\Course\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class CursoRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'sometimes|required|uuid|exists:cursos,id',
            'sigla' => 'required|max:3|string',
            'cnap' => 'max:150|string|nullable',
            'cbo_id' => 'required|uuid|exists:cbos,id',
            'nome' => 'required|max:50|string',
            'ativo' => 'required|int',
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
            'sigla' => 'Sigla',
            'cnap' => 'Nº CNAP',
            'cbo_id' => 'CBO',
            'nome' => 'Nome',
            'ativo' => 'Ativo'
        ];
    }
}

<?php


namespace Modules\Course\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class DisciplinaRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'sometimes|required|uuid|exists:disciplinas,id',
            'grade_id' => 'required|uuid|exists:grades,id',
            'sigla' => 'required|max:6|string',
            'nome' => 'required|max:80|string',
            'carga_horaria' => 'required|max:200|integer'
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
            'grade_id' => 'Grade ID',
            'sigla' => 'Sigla',
            'nome' => 'Nome',
            'carga_horaria' => 'Carga Horária'
        ];
    }
}

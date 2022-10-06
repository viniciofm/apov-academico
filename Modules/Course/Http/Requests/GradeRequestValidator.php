<?php


namespace Modules\Course\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class GradeRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'sometimes|required|uuid|exists:grades,id',
            'curso_id' => 'required|uuid|exists:cursos,id',
            'codigo' => 'required|max:15|string',
            'ano' => 'required|digits:4|integer',
            'periodo' => 'required|digits:1|integer',
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
            'codigo' => 'Código',
            'ano' => 'Ano',
            'periodo' => 'Periodo',
            'ativo' => 'Ativo'
        ];
    }
}

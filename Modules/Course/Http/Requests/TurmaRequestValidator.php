<?php


namespace Modules\Course\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class TurmaRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'sometimes|required|uuid|exists:turmas,id',
            'grade_id' => 'required|uuid|exists:grades,id',
            'codigo' => 'required|max:15|string',
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
            'codigo' => 'Código'
        ];
    }
}

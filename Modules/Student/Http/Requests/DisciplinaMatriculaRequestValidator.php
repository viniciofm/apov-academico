<?php


namespace Modules\Student\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class DisciplinaMatriculaRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'matricula_id' => 'required|uuid|exists:matriculas,id',
            'turma_id' => 'required|uuid|exists:turmas,id',
            'disciplinas' => 'sometimes|required|array',
            'disciplinas.*' => 'sometimes|required|uuid|exists:disciplinas,id',
        ];
    }

    protected function formatItems()
    {
        $items = json_decode($this->request->get('disciplinas'),true);
        $this->request->set('disciplinas' , $items);
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
            'matricula_id' => 'ID da Matrícula',
            'disciplinas' => 'Disciplinas',
            'disciplinas.*' => 'ID da Disciplina'
        ];
    }
}

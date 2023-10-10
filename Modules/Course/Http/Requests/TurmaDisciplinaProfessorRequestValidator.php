<?php


namespace Modules\Course\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class TurmaDisciplinaProfessorRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        if (!empty($this->request->get('professor_id'))) {
            $professorIdRule = 'required|uuid|exists:professores,id';
        } else {
            $professorIdRule = 'present|string|nullable';
        }

        return [
            'turma_disciplina_id' => 'required|uuid|exists:turma_disciplinas,id',
            'professor_id'        => $professorIdRule,
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
            'turma_disciplina_id' => 'ID',
            'professor_id' => 'Professor ID'
        ];
    }
}

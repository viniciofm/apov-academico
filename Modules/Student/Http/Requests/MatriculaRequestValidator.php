<?php


namespace Modules\Student\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class MatriculaRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'aluno_id' => 'required|uuid|exists:alunos,id',
            'grade_id' => 'required|uuid|exists:grades,id',
            'curso_id' => 'required|uuid|exists:cursos,id',
            'turma_id' => 'required|uuid|exists:turmas,id',
            'empresa_id' => 'sometimes|required|uuid|exists:empresas,id',
            'disciplinas' => 'sometimes|required|array',
            'disciplinas.id' => 'sometimes|required|uuid|exists:disciplinas,id',
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
            'aluno_id' => 'ID do Aluno',
            'grade_id' => 'ID da Grade',
            'curso_id' => 'ID do Curso',
            'empresa_id' => 'ID da Empresa',
            'turma_id' => 'ID da Turma',
            'disciplinas' => 'Disciplinas',
            'disciplinas.id' => 'ID da Disciplinas'
        ];
    }
}

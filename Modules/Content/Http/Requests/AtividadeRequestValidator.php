<?php


namespace Modules\Content\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;

class AtividadeRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'sometimes|required|uuid|exists:aulas,id',
            'turma_disciplina_id' => 'required|uuid|exists:turma_disciplinas,id',
            'data' => 'required|string',
            'titulo' => 'required|string|max:20',
            'descricao' => 'required|string|max:200',
        ];
    }

    protected function formatItems()
    {

    }

    public function getValidatorInstance()
    {
        return parent::getValidatorInstance();
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
            'descricao' => 'Descrição',
            'titulo' => 'Título',
            'turma_disciplina_id' => 'Conteúdo',
        ];
    }
}

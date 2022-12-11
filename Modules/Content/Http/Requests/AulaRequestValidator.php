<?php


namespace Modules\Content\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;

class AulaRequestValidator extends AbstractGenericFormRequest
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
            'conteudo' => 'required|string|max:500',
            'numero_aulas' => 'sometimes|int|max:3',
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
            'conteudo' => 'Conteúdo',
            'turma_disciplina_id' => 'Conteúdo',
            'numero_aulas' => 'Número de Aulas',
        ];
    }
}

<?php


namespace Modules\Student\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMatriculaRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'status' => 'required|string',
            'motivo_cancelamento' => 'sometimes|required|string',
            'data_saida' => 'sometimes|string',
            'empresa_id' => 'sometimes|uuid|exists:empresas,id|nullable'
        ];
    }

    protected function formatItems()
    {

    }

    public function getValidatorInstance(): \Illuminate\Contracts\Validation\Validator
    {
        $this->formatItems();

        return parent::getValidatorInstance();
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'status' => 'Status',
            'motivo_cancelamento' => 'Motivo Cancelamento',
            'data_saida' => 'Data de Saída',
            'empresa_id' => 'ID da Empresa'
        ];
    }
}

<?php


namespace Modules\User\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'sometimes|required|uuid|exists:users,id',
            'nome' => 'required|string',
            'email' => 'required|email|string',
            'cpf_cnpj' => 'required|string',
            'tipo_documento' => 'required|string',
            'genero_id' => 'required|uuid|exists:generos,id',
            'password' => 'sometimes|required|string',
            'endereco' => 'array',
            'endereco.id' => 'uuid|nullable',
            'endereco.rua' => 'string|nullable',
            'endereco.numero' => 'int|nullable',
            'endereco.bairro' => 'string|nullable',
            'endereco.complemento' => 'string|nullable',
            'endereco.cep' => 'string|nullable',
            'endereco.cidade_id' => 'uuid|nullable'
        ];
    }

    protected function formatItems()
    {
        $items = json_decode($this->request->get('endereco'),true);
        $this->request->set('endereco' , $items);
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
            'nome' => 'Nome',
            'email' => 'E-mail',
            'cpf_cnpj' => 'CPF/CNPJ',
            'tipo_documento' => 'Tipo do Documento',
            'genero_id' => 'Gênero',
            'password' => 'Senha',
            'endereco' => 'Endereço',
            'endereco.id' => 'ID do Endereço',
            'endereco.rua' => 'Rua',
            'endereco.numero' => 'Número',
            'endereco.bairro' => 'Bairro',
            'endereco.complemento' => 'Complemento',
            'endereco.cep' => 'CEP',
            'endereco.cidade_id' => 'ID da Cidade'
        ];
    }
}

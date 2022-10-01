<?php


namespace Modules\Student\Http\Requests;

use App\Http\Requests\AbstractGenericFormRequest;
use Illuminate\Support\Facades\Auth;

class AlunoRequestValidator extends AbstractGenericFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'sometimes|required|uuid|exists:empresas,id',
            'usuario' => 'array',
            'usuario.nome' => 'required|string',
            'usuario.email' => 'required|email|string',
            'usuario.cpf_cnpj' => 'required|string',
            'usuario.tipo_documento' => 'required|string',
            'usuario.genero_id' => 'required|uuid|exists:generos,id',
            'ativo' => 'required|int',
            'telefone' => '',
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
        $items = json_decode($this->request->get('usuario'),true);
        $this->request->set('usuario' , $items);
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
            'usuario.nome' => 'Nome',
            'usuario.email' => 'E-mail',
            'usuario.cpf_cnpj' => 'CPF/CNPJ',
            'usuario.tipo_documento' => 'Tipo do Documento',
            'usuario.genero_id' => 'Gênero',
            'ativo' => 'Ativo',
            'telefone' => 'Telefone',
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

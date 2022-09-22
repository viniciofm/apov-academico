<?php


namespace Modules\Company\Http\Requests;


use App\Http\Requests\AbstractGenericFormRequest;

class EmpresaRequestValidator extends AbstractGenericFormRequest
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
            'nome' => 'required|string',
            'email' => 'required|email|string',
            'responsavel' => 'required|string',
            'telefone_contato' => 'string|nullable',
            'cpf_cnpj' => 'required|string',
            'tipo_documento' => 'required|string',
            'logomarca' => 'mimes:jpeg,png,jpg|max:2048',
            'ativo' => 'required|string',
            'endereco' => 'array',
            'endereco.id' => 'uuid|nullable',
            'endereco.rua' => 'string|nullable',
            'endereco.numero' => 'string|nullable',
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
            'responsavel' => 'Responsável',
            'telefone_contato' => 'Telefone de Contato',
            'cpf_cnpj' => 'CPF/CNPJ',
            'tipo_documento' => 'Tipo do Documento',
            'logomarca' => 'Logomarca',
            'ativo' => 'Ativo',
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

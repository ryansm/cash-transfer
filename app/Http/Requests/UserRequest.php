<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => 'required|integer',
            'name' => 'required',
            'cpf_cnpj' => $this->isMethod('POST') ? 'required|unique:users|max:14' : 'required|max:14',
            'balance' => 'integer',
            'email' => $this->isMethod('POST') ? 'required|email|unique:users|max:255' : 'email|max:255',
            'password' => 'sometimes|required|min:8',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'unique' => 'Já existe esse :attribute registrado.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'email' => 'O campo :attribute não é um e-mail válido.',
            'integer' => 'O campo :attribute não é um número válido.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'type' => 'tipo',
            'name' => 'nome',
            'cpf_cnpj' => 'CPF - CNPJ',
            'balance' => 'saldo',
            'email' => 'e-mail',
            'password' => 'senha',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first();

        throw new HttpResponseException(
            response()->json([
                'message' => $message,
            ], Response::HTTP_BAD_REQUEST)
        );
    }
}

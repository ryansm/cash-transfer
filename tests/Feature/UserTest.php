<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private function postUser()
    {
        $response = $this->postJson('/api/user', [
            'type' => 0,
            'name' => 'John Doe',
            'cpf_cnpj' => '12345678901',
            'email' => 'john.doe@email.com',
            'password' => '12345678'
        ]);

        return $response;
    }

    /**
     * Test the creation of a new user.
     *
     * @return void
     */
    public function test_creating_a_new_user()
    {
        $response = $this->postUser();

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Usuário criado com sucesso!',
                'data' => [
                    'type' => 0,
                    'name' => 'John Doe',
                    'cpf_cnpj' => '12345678901',
                    'email' => 'john.doe@email.com',
                ]
            ]);
    }

    /**
     * Test the creation of a new user with empty name.
     *
     * @return void
     */
    public function test_creating_a_new_user_with_empty_name()
    {
        $response = $this->postJson('/api/user', [
            'type' => 0,
            'cpf_cnpj' => '12345678901',
            'email' => 'john.doe@email.com',
            'password' => '12345678'
        ]);

        $response
            ->assertStatus(400)
            ->assertExactJson(['message' => 'O campo nome é obrigatório.']);
    }

    /**
     * Test the creation of a user with an existing cpf_cnpj.
     *
     * @return void
     */
    public function test_creating_a_user_with_same_cpf_cnpj()
    {
        $response = $this->postUser();

        $response = $this->postJson('/api/user', [
            'type' => 0,
            'name' => 'John Doe',
            'cpf_cnpj' => '12345678901',
            'email' => 'john.doe2@email.com',
            'password' => '12345678'
        ]);

        $response
            ->assertStatus(400)
            ->assertExactJson(['message' => 'Já existe esse CPF - CNPJ registrado.']);
    }

    /**
     * Test the creation of a user with an existing e-mail.
     *
     * @return void
     */
    public function test_creating_a_user_with_same_email()
    {
        $response = $this->postUser();

        $response = $this->postJson('/api/user', [
            'type' => 0,
            'name' => 'John Doe 2',
            'cpf_cnpj' => '12345678901234',
            'email' => 'john.doe@email.com',
            'password' => '12345678'
        ]);

        $response
            ->assertStatus(400)
            ->assertExactJson(['message' => 'Já existe esse e-mail registrado.',]);
    }

    /**
     * Test the update of an existing user.
     *
     * @return void
     */
    public function test_updating_an_existing_user()
    {
        $response = $this->postUser();
        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->putJson('/api/user/' . $id, [
            'type' => 0,
            'name' => 'John Doe 2',
            'cpf_cnpj' => '12345678901',
            'email' => 'john.doe@email.com',
            'password' => '12345678'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Usuário atualizado com sucesso!',
                'data' => [
                    'type' => 0,
                    'name' => 'John Doe 2',
                    'cpf_cnpj' => '12345678901',
                    'email' => 'john.doe@email.com',
                ]
            ]);
    }

    /**
     * Test the update of an existing user setting an existing e-mail.
     *
     * @return void
     */
    public function test_updating_an_existing_user_with_an_existing_email()
    {
        $response = $this->postUser();
        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->postJson('/api/user', [
            'type' => 0,
            'name' => 'John Doe 2',
            'cpf_cnpj' => '12345678902',
            'email' => 'john.doe2@email.com',
            'password' => '12345678'
        ]);

        $response = $this->putJson('/api/user/' . $id, [
            'type' => 0,
            'name' => 'John Doe',
            'cpf_cnpj' => '12345678901',
            'email' => 'john.doe2@email.com',
            'password' => '12345678'
        ]);

        $response
            ->assertStatus(400)
            ->assertExactJson(['message' => 'Já existe um usuário com esse e-mail cadastrado.']);
    }

    /**
     * Test the delete of an existing user.
     *
     * @return void
     */
    public function test_deleting_an_existing_user()
    {
        $response = $this->postUser();
        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->deleteJson('/api/user/' . $id);

        $response
            ->assertStatus(200)
            ->assertExactJson(['message' => 'Usuário excluído com sucesso!']);
    }
}

<?php

namespace App\Services;

use App\Contracts\IUser;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService implements IUser
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all(): LengthAwarePaginator
    {
        try {
            $users = $this->userRepository->all();

            return $users;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function find(string $id): User
    {
        try {
            $user = $this->userRepository->find($id);

            if (empty($user->getAttributes())) {
                throw new Exception('Usuário não encontrado.');
            }

            return $user;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function create(array $data): User
    {
        $user = User::factory()->make($data);
        $user->password = Hash::make($user->password);

        DB::beginTransaction();

        try {
            $new_user_id = $this->userRepository->create($user->getAttributes());

            if ($new_user_id) {
                $res = $this->find($new_user_id);
            }

            DB::commit();

            return $res;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(array $data, string $id): User
    {
        $user = User::factory()->make($data);
        $user->setRawAttributes($data);

        if ($user->password) {
            $user->password = Hash::make($user->password);
        }

        DB::beginTransaction();

        try {
            $this->find($id);

            $cpf_cnpj_is_unique = true;
            $email_is_unique = true;

            if ($user->cpf_cnpj) {
                $cpf_cnpj_is_unique = $this->userRepository->isUnique('cpf_cnpj', $user->cpf_cnpj, $id);
            }

            if ($user->email) {
                $email_is_unique = $this->userRepository->isUnique('email', $user->email, $id);
            }

            if (!$cpf_cnpj_is_unique || !$email_is_unique) {
                $txt = !$cpf_cnpj_is_unique ? 'CPF-CNPJ' : 'e-mail';
                throw new Exception('Já existe um usuário com esse ' . $txt . ' cadastrado.');
            }

            $this->userRepository->update($user->getAttributes(), $id);

            $res = $this->find($id);

            DB::commit();

            return $res;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete($id): bool
    {
        DB::beginTransaction();

        try {
            $this->find($id);

            $res = $this->userRepository->delete($id);

            DB::commit();

            return $res;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}

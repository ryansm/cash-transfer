<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users.
     *
     * @return JsonResponse;
     */
    public function index() :JsonResponse
    {
        try {
            $users = $this->userService->all();

            return new JsonResponse($users, Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Show the profile for a given user.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $user = $this->userService->find($id);

            return new JsonResponse([
                'message' => 'Usuário encontrado com sucesso!',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a new user.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->create([
                'type' => $request->type,
                'name' => $request->name,
                'cpf_cnpj' => $request->cpf_cnpj,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return new JsonResponse([
                'message' => 'Usuário criado com sucesso!',
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the given user.
     *
     * @param UserRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UserRequest $request, $id): JsonResponse
    {
        try {
            $user = $this->userService->update([
                'type' => $request->type,
                'name' => $request->name,
                'cpf_cnpj' => $request->cpf_cnpj,
                'email' => $request->email,
                'password' => $request->password,
            ], $id);

            return new JsonResponse([
                'message' => 'Usuário atualizado com sucesso!',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete the given user.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->userService->delete($id);

            return new JsonResponse(['message' => 'Usuário excluído com sucesso!'], Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}

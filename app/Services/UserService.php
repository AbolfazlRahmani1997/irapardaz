<?php

namespace App\Services;


use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all()
    {
        return $this->userRepository->all();
    }

    public function find($id)
    {
        return $this->userRepository->find(id:$id);
    }

    public function create(array $data)
    {
        return $this->userRepository->create(data: $data);
    }

    public function update($id, array $data)
    {
        return $this->userRepository->update(id:$id,data: $data);
    }

    public function delete($id)
    {
        return $this->userRepository->delete(id:$id);
    }
}

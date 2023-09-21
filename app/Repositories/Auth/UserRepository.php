<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface{

    protected User $user;

    public function __construct(Model $user)
    {
        $this->user = $user;
    }

    public function all()
    {
        return $this->user->all();
    }

    public function find($id)
    {
        return $this->user->find($id);
    }

    public function create(array $data)
    {
        return $this->user->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->user->find($id);
        if(!$user)
            return false;

        return $user->update($data);
    }

    public function delete($id)
    {
        $user = $this->user->find($id);
        if(!$user)
            return false;

        return $user->delete();
    }
}

<?php

namespace App\Repositories\Auth;

use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function __construct(Model $model);
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

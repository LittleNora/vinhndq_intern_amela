<?php

namespace App\Repositories\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function updatePassword($id, $password);

    public function restore($id, $with = []);

    public function list(Request $request, $with = []);

    public function searchForUser(Request $request, $with = []);

//    public function login(array $data);
//
//    public function logout();
//
//    public function refresh();
//
//    public function me();
}

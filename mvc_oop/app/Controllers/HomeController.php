<?php

namespace App\Controllers;

use App\Models\User;

class HomeController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function index()
    {
        $users = $this->model->all();

        $this->view('home', ['users' => $users]);
    }
}
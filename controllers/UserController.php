<?php

class UserController
{
    public function index()
    {
        require_once __DIR__ . '/../views/user/index.php';
    }

    public function show($id)
    {
        require_once __DIR__ . '/../views/user/show.php';
    }
}

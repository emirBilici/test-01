<?php

class ResetPassword extends Controller
{

    public function index()
    {
        $this->view('auth/reset-password');
    }

}
<?php

class Auth extends Controller
{

    public function login()
    {
        if (session('login') || session('admin_login')) {
            site_url();
        }
        $this->view('auth/login');
    }

    public function signup()
    {
        if (session('login') || session('admin_login')) {
            site_url();
        }
        $this->view('auth/signup');
    }

    public function logout()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        unset($_SESSION['login']);
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        unset($_SESSION['user_created']);
        unset($_SESSION['user_votes']);

        site_url();
    }

    public function changeSettings()
    {
        $data = getData();

        if (isset($data->submit) && $data->submit === 'change_user_settings' && session('login')) {
            $model = $this->model('profileModel');
            $_SESSION['notifications'] = $data->notifications;

            if ($model->changeSettings($data)) {
                // updated data
                jsonOutput('user_settings_updated');
            } else {
                jsonOutput('error!');
            }
        }
    }

}
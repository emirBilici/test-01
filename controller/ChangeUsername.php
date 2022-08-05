<?php

class ChangeUsername extends Controller
{

    public function index()
    {
        if (!session('login')) {
            site_url();
        }

        $this->view('auth/change-username');
    }

    public function post()
    {
        $data = getData();
        $submit = $data->submit ?? false;
        $username = $data->newUsername ?? false;
        $password = $data->pass ?? false;

        if (!$submit || !$username || !$password) {
            jsonOutput([
                'message' => 'forbidden',
                'status' => 404
            ]);
        } else {
            $user = $this->model('loginModel')->checkUser(session('username'), $password);

            if ($user) {

                $checkUsername = $this->model('signupModel')->checkUserExist($username);
                if (!$checkUsername) {
                    $update = $this->model('profileModel')->updateUsername($username);

                    if ($update) {
                        jsonOutput('updated!');
                    } else {
                        jsonOutput('error!');
                    }
                } else {
                    jsonOutput('username_exist');
                }

            } else {
                jsonOutput('incorrect_password');
            }
        }
    }

}
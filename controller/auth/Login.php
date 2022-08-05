<?php

require realpath('.') . '/controller/Auth.php';

class Login extends Auth
{

    public function index()
    {
        $data = getData();
        $loginModel = $this->model('loginmodel');

        if ($data->submit === 'directly') {
            $check = $loginModel->checkUser($data->u, $data->p);
            if ($check) {
                $votes = $this->model('voteModel')->getUserVotes($check->user_id);
                $loginModel->Login($check, $votes);
                echo json_encode([
                    "accepted" => 1
                ]);
            } else {
                echo json_encode([
                    "accepted" => 0
                ]);
            }
            exit();
        } else if ($data->submit === "login-form") {
            $check = $loginModel->checkUserForLogin($data->username_or_email, $data->password);
            if ($check) {
                $votes = $this->model('voteModel')->getUserVotes($check->user_id);
                $loginModel->Login($check, $votes);
                $accept = 1;
            } else {
                $accept = 0;;
            }
            echo json_encode([
                "accepted" => $accept
            ]);
        }
    }

}
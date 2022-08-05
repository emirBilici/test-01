<?php

require realpath('.') . '/controller/Auth.php';

class Signup extends Auth
{

    public function index()
    {
        $data = getData();

        if ($data->submit === "register-form") {
            $this->createAccount($data);
        }
    }

    private function createAccount($data) {
        $signupModel = $this->model('signupmodel');
        if (!$signupModel->checkUserExist($data->username) && !$signupModel->checkEmailExist($data->email)) {

            if ($signupModel->Register($data)) {
                $message = "account_created";
                $targetId = "submit-btn";
                $status = 1;
            } else {
                // get error
                $message = "Bir hata oluştu ve kayıt olamadınız. Lütfen daha sonra tekrar deneyin.";
                $targetId = "";
                $status = 0;
            }

        } else {
            // get error
            $message = "Kullanıcı adı veya email adresi zaten kullanılıyor";
            $targetId = "userName";
            $status = 0;
        }
        echo message($message, $targetId, $status);
    }

}
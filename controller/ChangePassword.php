<?php

class ChangePassword extends Controller
{

    public function index()
    {
        if (!session('login')) {
            site_url('/login');
        }

        $this->view('auth/change-password');
    }

    public function post()
    {
        $data = getData();
        $old = $data->oldPass ?? false;
        $new = $data->confPass ?? false;
        $submit = $data->submit ?? false;

        if (!$old || !$new || !$submit || !session('login')) {
            jsonOutput([
                'message' => 'forbidden',
                'status' => 403
            ]);
        } else {
            $model = $this->model('profileModel');
            $change = $model->changePassword($old, $new);

            if ($change) {
                jsonOutput('updated!');
            } else {
                jsonOutput('error!');
            }
        }
    }

}
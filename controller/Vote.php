<?php

class Vote extends Controller
{

    public function post()
    {
        $data = getData();
        $postId = $data->post_id ?? false;
        $userId = $data->user_id ?? false;
        $type = $data->type ?? false;

        if (session('user_id') == $userId) {
            if (!$postId || !$userId || !$type) {
                jsonOutput('forbidden');
            } else {

                $model = $this->model('voteModel');
                $check = $model->checkVote($postId, $userId);

                if (!$check) {
                    $insert = $model->insertVote($postId, $userId, $type);

                    if ($insert) {
                        jsonOutput('voted!');
                    } else {
                        jsonOutput('error');
                    }
                } else {
                    if ($model->updateVote($type, $check)) {
                        jsonOutput('updated!');
                    } else {
                        jsonOutput('error');
                    }
                }

            }
        } else {
            jsonOutput('forbidden');
        }
    }

}
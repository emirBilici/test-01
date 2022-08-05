<?php

require_once realpath('.') . '/model/VoteModel.php';

class Profile extends Controller
{

    public function index($username)
    {
        $profileModel = $this->model('profilemodel');
        $check = $profileModel->checkUsername($username);

        if ($check) {
            $id = $check->user_id;
            $userPosts = $profileModel->getUserPosts($id);
            $vote = new VoteModel();
            foreach ($userPosts as $u_post => $userPost) {
                $userPost->entry_count = $vote->getPostVotesCount($userPost->post_id);
            }

            $this->view('profile', [
                'posts' => $userPosts,
                'user' => $check,
                'totalPoints' => $vote->getUserTotalPoints($id)
            ]);
        } else {
            $this->view('404', [
                'message' => 'Kullanıcı bulunamadı'
            ]);
        }
    }

}
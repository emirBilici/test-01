<?php

class Home extends Controller
{

    public function noSession()
    {
        $model = $this->model('homeModel');
        $defaultTag = $model->getDefaultTag();
        $entry = $model->getRandomEntry($defaultTag);

        require realpath('.') . '/parsedown/Parsedown.php';
        require realpath('.') . '/model/VoteModel.php';
        $parse = new Parsedown();
        $desc = $parse->text($entry->post_description);
        $vote = new VoteModel();
        $entry->entry_count = $vote->getPostVotesCount($entry->post_id);
        $entry->post_description = $desc;
        if ($entry->post_featured_code !== '_null_') {
            $entry->post_featured_code = $parse->text($entry->post_featured_code);
        }


        $this->view('home/no_session', [
            'defaultTag' => $defaultTag,
            'entry' => $entry
        ]);
    }

}
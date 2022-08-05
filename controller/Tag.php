<?php

require_once realpath('.') . '/model/VoteModel.php';

class Tag extends Controller
{

    public function index($slug)
    {
        $model = $this->model('tagModel');
        $check = $model->checkTag($slug);

        $posts = $model->posts($check->tag_id);
        $vote = new VoteModel();
        foreach ($posts as $u_post => $userPost) {
            $userPost->entry_count = $vote->getPostVotesCount($userPost->post_id);
        }

        if ($check) {
            $this->view('tag', [
                'data' => $check,
                'posts' => $posts
            ]);
        } else {
            $this->view('404', [
                'message' => 'Tag bulunamadı'
            ]);
        }
    }

    public function post()
    {
        $data = getData();
        $submit = $data->submit ?? false;
        $tagId = $data->tagId ?? false;

        if (!$submit || !$tagId) {
            jsonOutput([
                'message' => 'tag not found',
                'status' => 404
            ]);
        } else {

            $model = $this->model('tagModel');
            $tagData = $model->getTag($tagId);

            if (!$tagData) {
                jsonOutput('error');
            } else {
                $tagData->posts = $this->model('adminModel')->getTagPosts($tagData->tag_id);
                jsonOutput($tagData);
            }

        }
    }

    public function get()
    {
        $response = [];
        $tags = $this->model('adminModel')->getTags();
        foreach ($tags as $tag => $data) {
            $response[$tag] = [
                'name' => $data->name,
                'link' => '/tag/' . $data->slug,
                'id' => $data->tag_id
            ];
        }
        jsonOutput($response);
    }

}
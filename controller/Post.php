<?php

class Post extends Controller
{

    public function newPost()
    {
        if (!session('login')) {
            site_url('/login');
        }

        $postModel = $this->model("postmodel");
        $tags = $postModel->getTags();

        $this->view('newpost', [
            'tags' => $tags
        ]);
    }

    public function createNewPost()
    {
        LoginSession();
        $data = getData();
        $postModel = $this->model("postmodel");

        if ($data->submit === "new_post") {
            $create = $postModel->Create($data);

            if ($create) {
                echo json_encode("accepted");
            } else {
                echo json_encode("reject");
            }
        }
    }

    public function getPost(string $id)
    {
        $postModel = $this->model('postmodel');
        $data = $postModel->checkPost($id);

        if (!$data) {
            $this->view('404', [
                'message' => "Entry bulunamadı"
            ]);
        } else {
            require realpath('.') . '/parsedown/Parsedown.php';
            require realpath('.') . '/model/VoteModel.php';
            $parse = new Parsedown();
            $desc = $parse->text($data->post_description);
            $related = $postModel->getRelatedPosts($data->slug, $data->post_id);
            $vote = new VoteModel();
            $data->entry_count = $vote->getPostVotesCount($data->post_id);


            $this->view('post', [
                'data' => $data,
                'desc' => $desc,
                'related' => $related
            ]);
        }
    }

    public function deletePost()
    {
        $data = getData();
        if (!session('login')) {
            jsonOutput("forbidden!");
        } else {
            $model = $this->model('postModel');

            if ($data->submit === "delete" && $data->entry) {
                $check = $model->checkPostExist($data->entry);

                if ($check->user_id !== session('user_id')) {
                    jsonOutput("forbidden!");
                } else {
                    $delete = $model->Delete($check->post_id);

                    if ($delete) {
                        jsonOutput('post_deleted');
                    } else {
                        jsonOutput('post_not_deleted');
                    }
                }
            }
        }
    }

    public function reportEntry()
    {
        $key = get('key');

        if (!$key) {
            $this->view('404', [
                'message' => 'Geçersiz uri!'
            ]);
        } else {
            $model = $this->model('postModel');
            $data = $model->getPostWithKey($key);

            if (!$data) {
                site_url('/entry/' . $key);
            } else if (!session('login')) {
                site_url('/login?redirect_uri=/reportEntry?key=' . $key);
            } else {
                $this->view('reportentry', [
                    'data' => $data
                ]);
            }
        }
    }

    public function report()
    {
        $submit = get('submit') ?? false;
        $key = get('postKey') ?? false;
        $message = get('reporterMsg') ?? false;
        $reporterID = session('user_id') ?? false;

        if (!$submit || !$key || !$message || !$reporterID) {
            site_url();
        } else {
            $data = [
                's' => $submit,
                'k' => $key,
                'm' => $message,
                'i' => $reporterID
            ];
            $model = $this->model('postModel');
            if ($model->report($data)) {
                echo "post başarıyla rapor edildi, 2 saniye içinde yönlendirileceksiniz..";
                echo "<script>sessionStorage.setItem('reported', '1')</script>";
                header("Refresh:2;url=/?reported=1");
                exit();
            } else {
                echo "rapor edilemedi, lütfen tekrar deneyin!";
            }
        }
    }

}
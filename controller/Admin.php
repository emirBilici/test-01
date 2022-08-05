<?php

class Admin extends Controller
{

    /**
     * @return void
     */
    public function login(): void
    {
        if (!session('admin_session')) {
            $this->view('admin/login');
        } else {
            $model = $this->model('adminModel');
            $this->view('admin/dashboard', [
                'data' => [
                    'users' => $model->getUsers(),
                    'posts' => $model->getPosts(),
                    'tags' => $model->getTags(),
                    'reports' => $model->getReports(),
                    'default_tag' => $model->getDefaultTag()
                ]
            ]);
        }
    }

    /**
     * @return void
     */
    public function checkAdmin(): void
    {
        $data = getData();
        $reject = [
            'status' => 0
        ];
        $accept = [
            'status' => 1
        ];

        $submit = $data->submit ?? false;
        $email = $data->email?? false;
        $password = $data->password ?? false;

        if (!$submit || !$email || !$password) {
            jsonOutput($reject);
        }
        $model = $this->model('adminModel');
        $check = $model->checkAdminData($email, $password);

        if (!$check) {
            jsonOutput($reject);
        } else {
            $_SESSION['admin_session'] = 1;
            jsonOutput($accept);
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        $model = $this->model('adminModel');

        if (session('admin_session')) {
            if ($model->getUser($id)) {
                $profileModel = $this->model('profileModel');
                $userPosts = $profileModel->getUserPosts($id);

                for ($i = 0; $i < count($userPosts); $i++) {
                    $postId = $userPosts[$i]->post_id;
                    $model->deletePost($postId);
                }

                if ($model->deleteUser($id)) {
                    site_url('/admin');
                } else {
                    $model->getMessage('error', 504);
                }

            } else {
                $model->getMessage('user_not_found', 404);
            }
        } else {
            $model->getMessage('forbidden', 403);
        }
    }

    public function deleteTag(int $id)
    {
        $model = $this->model('adminModel');

        if (session('admin_session')) {
            if ($model->getTag($id)) {

                $tagPosts = $model->getTagPosts($id);
                for ($i = 0; $i < count($tagPosts); $i++) {
                    $postId = $tagPosts[$i]->post_id;
                    $model->deletePost((int) $postId);
                }

                if ($model->deleteTag($id)) {
                    site_url('/admin');
                } else {
                    $model->getMessage('error', 504);
                }

            } else {
                $model->getMessage('tag_not_found', 404);
            }
        } else {
            $model->getMessage('forbidden', 403);
        }
    }

    public function deleteReport(int $id)
    {
        $model = $this->model('adminModel');
        $delete = $model->deleteReport($id);

        if ($delete) {
            site_url('/admin?tab=reports');
        } else {
            die('report silinmedi!');
        }
    }

    public function createTag()
    {
        if (!session('admin_session') || !post('submit')) {
            site_url();
        }

        $name = post('name');
        $slug = Slug($name);
        $model = $this->model('adminModel');
        $check = $model->checkTag($slug);

        if (!$check) {
            $create = $model->newTag($name, $slug);

            if ($create) {
                site_url('/admin');
            } else {
                $model->getMessage('tag_not_created', 503);
            }
        } else {
            $model->getMessage('tag_already_exist', 0);
        }
    }

    public function changeDefaultTag()
    {
        if (!session('admin_session')) {
            site_url();
        }

        $submit = post('submit') ?? false;
        $tagId = post('homepage_default_tag') ?? false;
        if (!$submit || !$tagId) {
            site_url();
        } else {
            $model = $this->model('adminModel');
            $model->changeDefHomeTag($tagId);

            site_url('/admin');
        }
    }

}
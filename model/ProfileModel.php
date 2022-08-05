<?php

class ProfileModel extends Model
{

    /**
     * @param string $username
     * @return mixed
     */
    public function checkUsername(string $username): mixed
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $query->execute([
            'username' => input($username)
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param int $id
     * @return bool|array
     */
    public function getUserPosts(int $id): bool|array
    {
        $query = $this->db->prepare('SELECT p.post_id, p.post_title, p.post_featured_code, p.post_description, p.post_tag, p.puID, p.created_post, t.slug, t.name FROM posts AS p INNER JOIN users AS u ON u.user_id = p.post_user_id INNER JOIN tags AS t ON t.tag_id = p.post_tag WHERE u.user_id = :id ORDER BY p.post_id DESC');
        $query->execute([
            'id' => (int)input($id)
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param $data
     * @return bool
     */
    public function changeSettings($data): bool
    {
        if (strlen(input($data->about_me)) === 0 || !$data->openAboutMe) {
            $data->about_me = '_null_';
        }

        $query = $this->db->prepare('UPDATE users SET notifications = :notify, user_about = :about WHERE user_id = :id');
        $update = $query->execute([
            'notify' => $data->notifications,
            'about' => input($data->about_me),
            'id' => session('user_id')
        ]);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserById()
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE user_id = :id');
        $query->execute([
            'id' => session('user_id')
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function changePassword(string $old, string $new) :bool
    {
        $userPass = $this->getUserById();
        if ($userPass->password !== $this->encryptPassword($old)) {
            return false;
        } else {
            $query = $this->db->prepare('UPDATE users SET password = :password WHERE user_id = :id');
            $update = $query->execute([
                'password' => $this->encryptPassword($new),
                'id' => session('user_id')
            ]);

            if ($update) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param string $username
     * @return bool
     */
    public function updateUsername(string $username) :bool
    {
        $query = $this->db->prepare('UPDATE users SET username = :username WHERE user_id = :id');
        $update = $query->execute([
            'username' => $username,
            'id' => session('user_id')
        ]);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

}
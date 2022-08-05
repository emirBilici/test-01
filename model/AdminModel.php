<?php

class AdminModel extends Model
{

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function checkAdminData(string $email, string $password): bool
    {
        $adminData = $this->db->query('SELECT email, password FROM admin_settings')->fetch(PDO::FETCH_ASSOC);
        $adminEmail = $adminData['email'];
        $adminPassword = $adminData['password'];

        if ($this->encryptPassword($password) !== $adminPassword || $email !== $adminEmail) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return array|bool
     */
    public function getUsers(): bool|array
    {
        return $this->GetRows('users');
    }

    /**
     * @return array|false
     */
    public function getPosts(): bool|array
    {
        return $this->db->query('SELECT * FROM posts AS p INNER JOIN users AS u ON p.post_user_id = u.user_id INNER JOIN tags AS t ON p.post_tag = t.tag_id ORDER BY p.post_id DESC')->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @return array|bool
     */
    public function getTags(): bool|array
    {
        return $this->GetRows('tags');
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getUser(int $id): mixed
    {
        return $this->getRowWithWhere('users', 'user_id', $id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getTag(int $id): mixed
    {
        return $this->getRowWithWhere('tags', 'tag_id', $id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getReportData(int $id): mixed
    {
        return $this->getRowWithWhere('reports', 'report_id', $id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getNotification(int $id): mixed
    {
        return $this->getRowWithWhere('notifications', 'notification_id', $id);
    }

    /**
     * @param string $msg
     * @param int $code
     * @return void
     */
    public function getMessage(string $msg, int $code): void
    {
        jsonOutput([
            'message' => $msg,
            'statusCode' => $code
        ]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deletePost(int $id): bool
    {
        return $this->DeleteRow('posts', 'post_id', $id);
    }


    /**
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        return $this->DeleteRow('users', 'user_id', $id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteTag(int $id): bool
    {
        return $this->DeleteRow('tags', 'tag_id', $id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteReport(int $id): bool
    {
        return $this->DeleteRow('reports', 'report_id', $id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteNotification(int $id): bool
    {
        return $this->DeleteRow('notifications', 'notification_id', $id);
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public function checkTag(string $slug): mixed
    {
        $query = $this->db->prepare('SELECT * FROM tags WHERE slug = :s');
        $query->execute([
            's' => $slug
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param string $name
     * @param string $slug
     * @return bool
     */
    public function newTag(string $name, string $slug): bool
    {
        $query = $this->db->prepare('INSERT INTO tags SET name = :name, slug = :slug');
        $create = $query->execute([
            'name' => $name,
            'slug' => $slug
        ]);

        if ($create) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $id
     * @return array|false
     */
    public function getTagPosts(int $id): bool|array
    {
        $query = $this->db->prepare('SELECT * FROM posts WHERE post_tag = :id');
        $query->execute([
            'id' => $id
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @return array|false
     */
    public function getReports(): bool|array
    {
        return $this->db->query('SELECT * FROM reports INNER JOIN posts ON reports.reported_post_key = posts.puID INNER JOIN users ON users.user_id = reports.reporter_id ORDER BY reports.report_id DESC')->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $text
     * @param int $userId
     * @return bool
     */
    public function addNotification(string $text, int $userId): bool
    {
        $query = $this->db->prepare('INSERT INTO notifications SET notify_text = :text, notify_user = :userId');
        $insert = $query->execute([
            'text' => $text,
            'userId' => $userId
        ]);

        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $id
     * @return array|false
     */
    public function getUserNotifications(int $id): bool|array
    {
        $query = $this->db->prepare('SELECT * FROM notifications WHERE notify_user = :id ORDER BY notification_id DESC');
        $query->execute([
            'id' => $id
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int $tagId
     * @return bool
     */
    public function changeDefHomeTag(int $tagId): bool
    {
        $query = $this->db->prepare('UPDATE admin_settings SET homepage_default_tag = :id');
        $update = $query->execute([
            'id' => $tagId
        ]);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getDefaultTag(): mixed
    {
        return $this->db->query('SELECT homepage_default_tag FROM admin_settings')->fetch(PDO::FETCH_OBJ);
    }

}
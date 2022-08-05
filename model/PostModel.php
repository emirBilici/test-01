<?php

class PostModel extends Model
{

    /**
     * @param $data
     * @return bool
     */
    public function Create($data): bool
    {
        $ID = generateRandomString();
        if (strlen(trim($data->featured_code)) === 0) {
            $data->featured_code = "_null_";
        }
        $query = $this->db->prepare("INSERT INTO posts SET post_title = :title, post_featured_code = :code, post_description = :description, post_tag = :tag, post_user_id = :user_id, puID = :puID");
        $insert = $query->execute([
            'title' => input($data->post_title),
            'code' => input($data->featured_code),
            'description' => input($data->description),
            'tag' => input($data->tagIndex),
            'user_id' => session('user_id'),
            'puID' => $ID
        ]);

        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function checkPost(string $id): mixed
    {
        $query = $this->db->prepare('SELECT p.post_id, p.post_title, p.post_featured_code, p.post_description, p.puID, p.created_post, u.user_id, u.username, u.email, u.user_created, t.slug, t.name FROM posts AS p INNER JOIN users AS u ON u.user_id = p.post_user_id INNER JOIN tags AS t ON t.tag_id = p.post_tag WHERE puID = :id');
        $query->execute([
            'id' => $id
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @return array|false
     */
    public function getTags(): bool|array
    {
        return $this->db->query('SELECT * FROM tags')->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function checkPostExist($data): mixed
    {
        $query = $this->db->prepare('SELECT * FROM posts INNER JOIN users ON posts.post_user_id = users.user_id WHERE post_id = :id && puID = :key');
        $query->execute([
            'id' => $data->id,
            'key' => $data->key
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function Delete(int $id): bool
    {
        $query = $this->db->prepare('DELETE FROM posts WHERE post_id = :id');
        $delete = $query->execute([
            'id' => $id
        ]);

        if ($delete) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $tag_slug
     * @param $post_id
     * @return array|false
     */
    public function getRelatedPosts($tag_slug, $post_id): bool|array
    {
        $query = $this->db->prepare('SELECT p.post_title, p.puID FROM posts AS p INNER JOIN tags AS t ON p.post_tag = t.tag_id WHERE t.slug = :tag_slug && p.post_id NOT IN('.$post_id.') ORDER BY RAND() LIMIT 5');
        $query->execute([
            'tag_slug' => $tag_slug
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getPostWithKey(string $key): mixed
    {
        $query = $this->db->prepare('SELECT * FROM posts WHERE puID = :key');
        $query->execute([
            'key' => $key
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function report(array $data): bool
    {
        $query = $this->db->prepare('INSERT INTO reports SET reporter_id = :i, reported_post_key = :k, report_message = :m');
        $insert = $query->execute([
            'i' => $data['i'],
            'k' => $data['k'],
            'm' => $data['m']
        ]);

        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

}
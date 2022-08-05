<?php

class HomeModel extends Model
{

    public function getDefaultTag()
    {
        return $this->db->query('SELECT homepage_default_tag FROM admin_settings')->fetch(PDO::FETCH_OBJ)->homepage_default_tag;
    }

    public function getRandomEntry(int $tagId)
    {
        $query = $this->db->prepare('SELECT * FROM posts INNER JOIN tags ON tags.tag_id = posts.post_tag INNER JOIN users ON users.user_id = posts.post_user_id WHERE tags.tag_id = :id ORDER BY RAND() LIMIT 1');
        $query->execute([
            'id' => $tagId
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

}
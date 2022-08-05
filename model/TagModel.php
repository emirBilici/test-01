<?php

class TagModel extends Model
{

    /**
     * @param $slug
     * @return mixed
     */
    public function checkTag($slug): mixed
    {
        $query = $this->db->prepare('SELECT * FROM tags WHERE slug = :slug');
        $query->execute([
            'slug' => $slug
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param $id
     * @return bool|array
     */
    public function posts($id): bool|array
    {
        $query = $this->db->prepare('SELECT * FROM tags RIGHT JOIN posts ON posts.post_tag = tags.tag_id RIGHT JOIN users ON users.user_id = posts.post_user_id WHERE tag_id = :id ORDER BY posts.post_id DESC');
        $query->execute([
            'id' => $id
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getTag(int $id): mixed
    {
        return $this->getRowWithWhere('tags', 'tag_id', $id);
    }

}
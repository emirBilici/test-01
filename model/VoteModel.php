<?php

class VoteModel extends Model
{

    /**
     * @param $postId
     * @param $userId
     * @param $type
     * @return bool
     */
    public function insertVote($postId, $userId, $type): bool
    {
        $query = $this->db->prepare('INSERT INTO vote SET vote_entry = :postId, user_vote = :userId, status = :type');
        $insert = $query->execute([
            'postId' => $postId,
            'userId' => $userId,
            'type' => $type
        ]);

        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $postId
     * @param $userId
     * @return mixed
     */
    public function checkVote($postId, $userId): mixed
    {
        $query = $this->db->prepare('SELECT * FROM vote WHERE vote_entry = :postId && user_vote = :userId');
        $query->execute([
            'postId' => $postId,
            'userId' => $userId
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param $status
     * @param $data
     * @return bool
     */
    public function updateVote($status, $data): bool
    {
        $query = $this->db->prepare('UPDATE vote SET status = :status WHERE vote_entry = :postId && user_vote = :userId');
        $update = $query->execute([
            'status' => $status,
            'postId' => $data->vote_entry,
            'userId' => $data->user_vote
        ]);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $userId
     * @return array|false
     */
    public function getUserVotes(int $userId): bool|array
    {
        $query = $this->db->prepare('SELECT vote.vote_entry, vote.status, vote.user_vote FROM vote LEFT JOIN users ON vote.user_vote = :id');
        $query->execute([
            'id' => $userId
        ]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return array|false
     */
    public function getPostVotes(int $id): bool|array
    {
        $query = $this->db->prepare('SELECT * FROM vote WHERE vote_entry = :id');
        $query->execute([
            'id' => $id
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getVoteStatus(int $id, int $type): bool|array
    {
        $query = $this->db->prepare('SELECT * FROM vote WHERE vote_entry = :id && status = :status');
        $query->execute([
            'id' => $id,
            'status' => $type
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int $postId
     * @return int
     */
    public function getPostVotesCount(int $postId): int
    {
        $count = count(self::getPostVotes($postId));
        if ($count !== 0) {
            $up = count(self::getVoteStatus($postId, 1));
            $down = count(self::getVoteStatus($postId, 2));

            return ($up - $down);
        } else {
            return 0;
        }
    }

    /**
     * @param int $userId
     * @return array|false
     */
    protected function getUserUpVotes(int $userId): bool|array
    {
        $query = $this->db->prepare('SELECT * FROM vote WHERE user_vote = :id && status = 1');
        $query->execute([
            'id' => $userId
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int $userId
     * @return bool|array
     */
    protected function getUserDownVotes(int $userId): bool|array
    {
        $query = $this->db->prepare('SELECT * FROM vote WHERE user_vote = :id && status = 2');
        $query->execute([
            'id' => $userId
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param $userId
     * @return int
     */
    public function getUserTotalPoints($userId): int
    {
        $upVotes = count($this->getUserUpVotes($userId));
        $downVotes = count($this->getUserDownVotes($userId));

        return $upVotes - $downVotes;
    }

}
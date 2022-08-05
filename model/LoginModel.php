<?php

class LoginModel extends Model
{

    /**
     * @param $username
     * @param $password
     * @return mixed
     */
    public function checkUser($username, $password): mixed
    {
        $password = $this->encryptPassword($password);

        $query = $this->db->prepare('SELECT * FROM users WHERE username = :username && password = :password');
        $query->execute([
            'username' => input($username),
            'password' => input($password)
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param $username_or_email
     * @param $password
     * @return mixed
     */
    public function checkUserForLogin($username_or_email, $password): mixed
    {
        $password = $this->encryptPassword($password);
        if (!filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
            // username
            $query = $this->db->prepare('SELECT * FROM users WHERE username = :username && password = :password');
            $query->execute([
                'username' => input($username_or_email),
                'password' => input($password)
            ]);
        } else {
            // email
            $query = $this->db->prepare('SELECT * FROM users WHERE email = :email && password = :password');
            $query->execute([
                'email' => input($username_or_email),
                'password' => input($password)
            ]);
        }
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param $data
     * @param $votes
     * @return void
     */
    public function Login($data, $votes): void
    {
        $_SESSION['login'] = 1;
        $_SESSION['user_id'] = $data->user_id;
        $_SESSION['username'] = $data->username;
        $_SESSION['email'] = $data->email;
        $_SESSION['notifications'] = $data->notifications;
        $_SESSION['user_created'] = $data->user_created;
        $_SESSION['user_votes'] = $votes;
    }

}
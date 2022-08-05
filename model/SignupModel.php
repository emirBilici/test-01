<?php

class SignupModel extends Model
{

    /**
     * @param $username
     * @return mixed
     */
    public function checkUserExist($username): mixed
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $query->execute([
            'username' => input($username)
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function checkEmailExist($email): mixed
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $query->execute([
            'email' => input($email)
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param $data
     * @return bool
     */
    public function Register($data): bool
    {
        $query = $this->db->prepare('INSERT INTO users SET username = :username, email = :email, password = :password, user_about = :about');
        $insert = $query->execute([
            'username' => input($data->username),
            'email' => input($data->email),
            'password' => input($this->encryptPassword($data->password)),
            'about' => '_null_'
        ]);

        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

}
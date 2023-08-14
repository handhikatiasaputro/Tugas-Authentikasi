<?php
session_start();

include_once 'DB.php';

class Auth extends DB 
{
    public static function register($data)
    {
        $name = $data['name'];
        $username = $data['username'];
        $password = $data['password'];
        $confrim_password = $data['confrim_password'];

        if($password === $confrim_password) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users(name,username,password) VALUES ('$name', '$username', '$password')";
            $result = DB:: connect()->query($sql);

            return [
                "status" => "success",
                "data" => $result,
                "message" => "Berhasil register",
            ];
        }
        else{
            return [
                "status" => "error",
                "data" => [],
                "message" => "Password tidak cocok",
            ];
        }
    }

    public static function login($data)
    {
        $username = $data["username"];
        $password = $data["password"]; 

        $user = Auth::CheckUsername($username);
        if($user === null) {
            return [
                "status" => "error",
                "data" => [],
                "message" => "Username tidak ditemukan",
            ];
        }
        else{
            if($decrpty) {
                return [
                    "status" => "error",
                    "data" => [],
                    "message" => "Password salah",
                ];
            }
            else{
                $_SESSION["username"] = $username;
                setcookie("username", $username, time() + 86400);

                header("Location: home.php");
            }
        }
    }
    public static function checkUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = DB::connect()->query($sql)->fetch_assoc();

        return $result;
    }

    public static function checkPassword($password, $password_hash)
    {
        $decrpty = password_verify($password, $password_hash);

        return $decrpty;
    }
}

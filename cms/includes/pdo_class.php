<?php
/**
 * Created by PhpStorm.
 * User: GRE-ENG
 * Date: 1/26/2015
 * Time: 7:53 PM
 */

class dBQuery
{
    protected $pdo;

    public function __construct()
    {
        include_once("settings/settings.php");
        $this->pdo = new PDO("$dbtype:host=$host;dbname=$database". $username, $pass);
    }

    public function sendpQuery($query, array $args)
    {
        $stmt = $this->pdo->prepare($query);
    }


    public function fetchUserData($uname, $pass)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE uname = :uname AND passwd = :passwd');
        $stmt->bindParam(':uname',$uname);
        $stmt->bindParam(':passwd',$pass);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function userExists($uname, $pass)
    {
        return (count($this->fetchUserData($uname, $pass)) == 1);
    }


}
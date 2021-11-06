<?php

/**
 * Logger Api
 *
 * This is a advanced logger class
 *
 *
 * @link www.omerfd,com
 * @since 1.0.0
 *
 * @version 1.0.0
 *
 * @package Omerfdmrl\Logger
 * 
 * @licence: The MIT License (MIT) - Copyright (c) - http://opensource.org/licenses/MIT
 */


namespace Omerfdmrl\Logger;

use Jenssegers\Agent\Agent;

class Logger
{
    /**
     * @var $agent For Agent Class
     */
    public static $agent;

    /**
     * @var $db For Database Connection
     */
    public static $db;

    /**
     * @var string $db_type For Database Connection Type
     */
    public static string $db_type;

    /**
     * @var string|int $db_name For Database Column Name
     */
    public static string|int $db_name = 'logs';

    /**
     * @var string $sql For Create Database Sql Code
     */
    protected static string $sql;

    /**
     * @var $error For Return Error
     */
    protected static string|null $error = NULL;

    public function __construct()
    {
        self::$agent = new Agent;
        self::$agent->setUserAgent($_SERVER['HTTP_USER_AGENT']);
    }

    /**
     * For Set Database Veriable
     * 
     * @param $db
     * @param string $type
     * @param bool $createTable
     * @param string|int $dbname
     */
    public function db($db,string $type = 'PDO',bool $createTable = True,string|int $dbname = 'logs'):void
    {
        self::$db = $db;
        self::$db_type = $type;
        self::$db_name = $dbname;
        if($createTable){
            self::createTable();
        }
    }


    /**
     * For Save log
     * 
     * @param string|int $title
     * @param string|int $message
     * @param int $user_id
     */
    public static function save(string|int $title,string|int $message,int $user_id = 0,int|string $type): bool
    {
        if(!self::error_control()){
            return False;
        }else {
            $device = self::$agent->device();
            $platform = self::$agent->platform();
            $platform_version = self::$agent->version($platform);
            $browser = self::$agent->browser();
            $browser_version = self::$agent->version($browser);
            return self::insert([$user_id,$title,$type,$message,date('Y-m-d H:i:s'),$device,$platform,$platform_version,$browser,$browser_version]);
        }
    }

    /**
     * For Drop Table
     */
    public static function drop(): bool
    {
        if(!self::error_control()){
            return False;
        }elseif(self::get_type()){
            return self::pdo_drop();
        }elseif(self::get_type('mysqli')){
            return self::mysqli_drop();
        }
    }

    /**
     * For Truncate Table
     */
    public static function truncate(): bool
    {
        if(!self::error_control()){
            return False;
        }elseif(self::get_type()){
            return self::pdo_truncate();
        }elseif(self::get_type('mysqli')){
            return self::mysqli_truncate();
        }
    }

    /**
     * For Get Error
     */
    public static function get_error(): string|bool|null|int
    {
        return self::$error;
    }

    protected static function error_control(): bool
    {
        if(self::$db == NULL){
            self::$error = "Connection Error. Please call db() function";
            return False;
        }else {
            return True;
        }
    }

    protected static function set_sql():void
    {
        self::$sql = "CREATE TABLE IF NOT EXISTS `".self::$db_name."`(
            id int AUTO_INCREMENT NOT NULL,
            user_id int NOT NULL DEFAULT 0,
            date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            type int NOT NULL DEFAULT 0,
            device varchar(250),
            platform varchar(150), 
            platform_version varchar(150), 
            browser varchar(150), 
            browser_version varchar(100),
            title varchar(300),
            message varchar(600),
            primary key(id)
            )";
    }

    protected static function get_type($type = 'pdo'):bool
    {
        if(strtolower(self::$db_type) == $type){
            return True;
        }else {
            return False;
        }
    }

    protected static function createTable(): void
    {
        if(self::get_type()){
            self::pdo_createTable();
        }elseif(self::get_type('mysqli')){
            self::mysqli_createTable();
        }
    }

    protected static function insert(array $execute): bool
    {
        if(self::get_type()){
            return self::pdo_insert($execute);
        }elseif(self::get_type('mysqli')){
            return self::mysqli_insert($execute);
        }
    }

    protected static function pdo_insert(array $execute): bool
    {
        $insert = self::$db->prepare('INSERT INTO ' . self::$db_name . ' SET user_id=?, title=?, type=?, message=?, date=?, device=?, platform=?, platform_version=?, browser=?, browser_version=?');
        $insert->execute($execute);
        if($insert){
            return True;
        }else {
            return False;
        }
    }

    protected static function mysqli_insert(array $execute): bool
    {
        $insert = self::$db->query('INSERT INTO ' . self::$db_name . ' (user_id, title, type, message, date, device, platform, platform_version, browser, browser_version) VALUES ('.$execute[0].',"'.$execute[1].'","'.$execute[2].'","'.$execute[3].'","'.$execute[4].'","'.$execute[5].'","'.$execute[6].'","'.$execute[7].'","'.$execute[8].'","'.$execute[9].'")');
        if($insert){
            return True;
        }else {
            return False;
        }
    }

    protected static function pdo_drop(): bool
    {

        if(self::$db->prepare('DROP TABLE ' . self::$db_name)->execute()){
            return True;
        }else {
            return False;
        }
    }

    protected static function mysqli_drop(): bool
    {

        if(self::$db->query('DROP TABLE ' . self::$db_name)){
            return True;
        }else {
            return False;
        }
    }

    protected static function pdo_truncate(): bool
    {
        if(self::$db->prepare('TRUNCATE TABLE ' . self::$db_name)->execute()){
            return True;
        }else {
            return False;
        }
    } 

    protected static function mysqli_truncate(): bool
    {
        if(self::$db->query('TRUNCATE TABLE ' . self::$db_name)){
            return True;
        }else {
            return False;
        }
    }

    protected static function pdo_createTable():void
    {
        self::set_sql();
        self::$db->exec(self::$sql);
    }

    protected static function mysqli_createTable():void
    {
        self::set_sql();
        self::$db->query(self::$sql);
    }
}
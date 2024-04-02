<?php 

if($_SERVER['SERVER_NAME'] == "localhost")
{

	define("ROOT", "http://localhost:84/music_websites/public");
	define("DBDRIVER", "mysql");
	define("DBHOST", "localhost");
	define("DBUSER", "root");
	define("DBPASS", "");
	define("DBNAME", "music_website_db");

}else{


	define("DBDRIVER", "mysql");
	define("DBHOST", "localhost");
	define("DBUSER", "root");
	define("DBPASS", "");
	define("DBNAME", "music_website_db");
}

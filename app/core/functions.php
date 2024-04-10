<?php 
session_start();
include "../app/core/config.php";
function show($stuff)
{
	echo "<pre>";
	print_r($stuff);
	echo "</pre>";
}

function page($file)
{

	return "../app/pages/".$file.".php";
}

function db_connect()
{
	$string = DBDRIVER.":hostname=".DBHOST.";dbname=".DBNAME;
	$con = new PDO($string, DBUSER, DBPASS);

	return $con;
}

function db_query($query, $data = array())
{
	$con = db_connect();

	$stm = $con->prepare($query);
	if($stm)
	{
		$check = $stm->execute($data);
		if($check){
			$result = $stm->fetchAll(PDO::FETCH_ASSOC);

			if(is_array($result) && count($result) > 0)
			{
				return $result;
			}
		}
	}
	return false;
}

function db_query_one($query, $data = array())
{
	$con = db_connect();

	$stm = $con->prepare($query);
	if($stm)
	{
		$check = $stm->execute($data);
		if($check){
			$result = $stm->fetchAll(PDO::FETCH_ASSOC);

			if(is_array($result) && count($result) > 0)
			{
				return $result[0];
			}
		}
	}
	return false;
}

function db_query_insert($query, $data = array()) //insert db
{
    $con = db_connect();
    $stm = $con->prepare($query);
    if($stm)
    {
        $check = $stm->execute($data);
        if($check){
            return $stm->rowCount();
        }
    }
    return false;
}

function message($message = '', $clear = false)
{
	if(!empty($message)){
		$_SESSION['message'] = $message;
	}else{

		if(!empty($_SESSION['message'])){

			$msg = $_SESSION['message'];
			if($clear){
				unset($_SESSION['message']);
			}
			return $msg;
		}

	}
	return false;
}

function redirect($page)
{
	header("Location: ".ROOT."/".$page);
	die;
}

function set_value($key, $default = '')
{
	if(!empty($_POST[$key]))
	{
		return $_POST[$key];
	}else{

		return $default;
	}
}

function set_select($key, $value, $default = '')
{
	if(!empty($_POST[$key]))
	{
		if($_POST[$key] == $value){
			return " selected ";
		}
	}else{
		if($default == $value){
			return " selected ";
		}
	}

	return '';
}

function get_date($date)
{
	return date("jS M, Y",strtotime($date));
}

function logged_in()
{

	if(!empty($_SESSION['USER']) && is_array($_SESSION['USER'])){
		return true;
	}

	return false;
}



function is_user() //Kiểm tra role user
{

	if(!empty($_SESSION['USER']['role']) && $_SESSION['USER']['role'] == 'user'){
		return true;
	}

	return false;
}

function user($column)
{
	if(!empty($_SESSION['USER'][$column])){
		return $_SESSION['USER'][$column];
	}

	return "Unknown";
}

function authenticate($row)
{
	$_SESSION['USER'] = $row;
}

function str_to_url($url)
{

	$url = str_replace("'", "", $url);
   	$url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
   	$url = trim($url, "-");
   	$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
   	$url = strtolower($url);
   	$url = preg_replace('~[^-a-z0-9_]+~', '', $url);
   	
   	return $url;
}

function get_category($id)
{
	$query = "select category from categories where id = :id limit 1";
	$row = db_query_one($query,['id'=>$id]);

	if(!empty($row['category']))
	{
		return $row['category'];
	}

	return "Unknown";
}

function esc($str)
{
	return nl2br(htmlspecialchars($str));
}

function get_artist($id)
{
	$query = "select name from artists where id = :id limit 1";
	$row = db_query_one($query,['id'=>$id]);

	if(!empty($row['name']))
	{
		return $row['name'];
	}

	return "Unknown";
}

// tu day la code kien xu ly index---------------------------------------------------------------------
function get_user_playlists($uid) {
	$query = "SELECT user_playlists.pid, playlist.playlist_name
			FROM user_playlists
			JOIN playlist ON user_playlists.pid = playlist.pid
			WHERE uid = ?";
	return db_query($query, array($uid));
}

function get_albums() {
	$query = "SELECT albums.abid, albums.title, albums.album_image
			FROM albums
			INNER JOIN songs ON albums.sid = songs.id
			WHERE albums.status = 1
			GROUP BY albums.abid";
	return db_query($query);
}

function get_artists() {
	$query = "SELECT id AS aid, name AS artist_name, image AS artist_image
			FROM artists";
	return db_query($query);
}

function get_songs_by_playlist($pid) {
	$query = "SELECT songs.id AS sid, songs.title, artists.name AS artist_name, songs.image AS song_image, songs.file AS file_path
			FROM songs
			INNER JOIN user_playlists ON songs.id = user_playlists.sid
			INNER JOIN artists ON songs.artist_id = artists.id
			WHERE user_playlists.pid = ?";
	return db_query($query, array($pid));
}

function get_songs_by_album($abid) {
	$query = "SELECT songs.id AS sid, songs.title, artists.name AS artist_name, songs.image AS song_image, songs.file AS file_path
			FROM songs
			INNER JOIN albums ON songs.id = albums.sid
			INNER JOIN artists ON songs.artist_id = artists.id
			WHERE abid = ?";
	return db_query($query, array($abid));
}

function get_songs_by_artist($aid) {
	$query = "SELECT songs.id AS sid, songs.title, artists.name AS artist_name, songs.image AS song_image, songs.file AS file_path
			FROM songs 
			INNER JOIN artists ON songs.artist_id = artists.id
			WHERE songs.artist_id = ?";
	return db_query($query, array($aid));
}

function get_favorite_songs($uid) {
	$query = "SELECT songs.id AS sid, songs.title, artists.name AS artist_name, songs.image AS song_image, songs.file AS file_path
			FROM user_playlists
			INNER JOIN songs ON user_playlists.sid = songs.id
			INNER JOIN artists ON songs.artist_id = artists.id
			WHERE user_playlists.uid = ? AND user_playlists.favorite = 1";
	return db_query($query, array($uid));
}

function get_top_songs($limit) {
	$query = "SELECT songs.id AS sid, songs.title, artists.name AS artist_name, songs.image AS song_image, songs.file AS file_path
			FROM user_playlists
			INNER JOIN songs ON user_playlists.sid = songs.id
			INNER JOIN artists ON songs.artist_id = artists.id
			GROUP BY songs.id
			ORDER BY COUNT(user_playlists.sid) DESC
			LIMIT ?";
	return db_query($query, array($limit));
}

function get_all_songs(){
	$query = "SELECT songs.id AS sid, songs.title, artists.name AS artist_name, songs.image AS song_image, songs.file AS file_path
			FROM songs
			INNER JOIN artists ON songs.artist_id = artists.id";
	return db_query($query);
}

function displaySongs($songs) {
	if ($songs !== false) {
		$count = 1;
		foreach ($songs as $song) {
			$count_str = str_pad($count, 2, '0', STR_PAD_LEFT);
			echo "<li class='songItem' onclick='loadSong(\"{$song['title']}\", \"{$song['artist_name']}\",
				\"{$song['song_image']}\", \"{$song['file_path']}\")'>";
			echo "<span>{$count_str}</span>";
			echo "<img src='{$song['song_image']}' alt=''>";
			echo "<h5>{$song['title']} <br> <div class='subtitle'>{$song['artist_name']}</div></h5>";
			echo "<i class='bi playlistPlay bi-play-fill' id='{$song['sid']}'></i>";
			echo "</li>";
			$count++;
		}
	} else {
		echo "<p>No songs available.</p>";
	}
}
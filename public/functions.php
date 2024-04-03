<?php 
	session_start();
	define("ROOT", "http://localhost:84/music_websites/public");
	define("DBDRIVER", "mysql");
	define("DBHOST", "localhost");
	define("DBUSER", "root");
	define("DBPASS", "");
	define("DBNAME", "musicweb");
	function db_connect(){
		$string = DBDRIVER.":hostname=".DBHOST.";dbname=".DBNAME;
		$con = new PDO($string, DBUSER, DBPASS);

		return $con;
	}
	function db_query($query, $data = array()){
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
	function db_query_one($query, $data = array()){
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
	function get_user_playlists($uid) {
		$query = "SELECT user_playlists.pid, playlist_name
				FROM user_playlists
				JOIN playlist ON user_playlists.pid = playlist.pid
				WHERE uid = ?";
		return db_query($query, array($uid));
	}
	function get_albums() {
		$query = "SELECT abid, title, album_image
				FROM albums
				WHERE status = 1
				GROUP BY abid";
		return db_query($query);
	}
	function get_artists() {
		$query = "SELECT aid, artist_name, artist_image
				FROM artists";
		return db_query($query);
	}
	function get_songs_by_playlist($pid) {
		$query = "SELECT songs.sid, songs.title, artists.artist_name, songs.song_image, songs.file_path
				FROM songs
				INNER JOIN user_playlists ON songs.sid = user_playlists.sid
				INNER JOIN artists ON songs.aid = artists.aid
				WHERE user_playlists.pid = ?";
		return db_query($query, array($pid));
	}
	function get_songs_by_album($abid) {
		$query = "SELECT songs.sid, songs.title, artists.artist_name, songs.song_image, songs.file_path
				FROM songs
				INNER JOIN albums ON songs.sid = albums.sid
				INNER JOIN artists ON songs.aid = artists.aid
				WHERE abid = ?";
		return db_query($query, array($abid));
	}
	function get_songs_by_artist($aid) {
		$query = "SELECT songs.sid, songs.title, artist_name, songs.song_image, songs.file_path
				FROM songs 
				INNER JOIN artists ON songs.aid = artists.aid
				WHERE songs.aid = ?";
		return db_query($query, array($aid));
	}
	function get_favorite_songs($uid) {
		$query = "SELECT songs.sid, songs.title, artists.artist_name, songs.song_image, songs.file_path
				FROM user_playlists
				INNER JOIN songs ON user_playlists.sid = songs.sid
				INNER JOIN artists ON songs.aid = artists.aid
				WHERE user_playlists.uid = ? AND user_playlists.favorite = 1";
		return db_query($query, array($uid));
	}
	
	function get_top_songs($limit) {
		$query = "SELECT songs.sid, songs.title, artists.artist_name, songs.song_image, songs.file_path
				FROM user_playlists
				INNER JOIN songs ON user_playlists.sid = songs.sid
				INNER JOIN artists ON songs.aid = artists.aid
				GROUP BY songs.sid
				ORDER BY COUNT(user_playlists.sid) DESC
				LIMIT ?";
		return db_query($query, array($limit));
	}
	function get_all_songs(){
		$query = "SELECT songs.sid, songs.title, artists.artist_name, songs.song_image, songs.file_path
		FROM songs
		INNER JOIN artists ON songs.aid = artists.aid";
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

?>
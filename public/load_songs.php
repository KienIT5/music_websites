<?php
include "functions.php";

if(isset($_SESSION['uid']) && isset($_GET['option']) && isset($_GET['id'])) {
    $option = $_GET['option'];
    $id = $_GET['id'];
    $uid = $_SESSION['uid'];

    if($option === 'playlist') {
        $songs = get_songs_by_playlist($id);
    } elseif($option === 'album') {
        $songs = get_songs_by_album($id);
    } elseif($option === 'artist') {
        $songs = get_songs_by_artist($id);
    } elseif( $option === 'all'){
        $songs = get_all_songs();
    } elseif( $option === 'like'){
        get_favorite_songs($uid);
    } elseif( $option === 'top'){
        $songs = get_top_songs(10);
    }

    displaySongs($songs);
}
?>
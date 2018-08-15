<?php

require_once './YPTMP3/youtube_playlist_to_mp3.php';

$playlist_url = "https://www.youtube.com/playlist?list=PLP4oWxNAmbBoAPhJv0DMDOUhtB3jObLL5";

$downloaded = (new Youtube_playlist_to_mp3($playlist_url))->list_downloaded();
print_r($downloaded);
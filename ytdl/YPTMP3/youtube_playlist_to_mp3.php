<?php

require_once 'simple_html_dom.php';

/*
 *
 * download mp3 from a youtube playlist
 * requirment:
 * your system: youtube-dl with avtools installed whch will be used for encoding into mp3
 * ffmpeg is not yet tested
 * php: simple html dom: http://simplehtmldom.sourceforge.net/
 * 
 */
class Youtube_playlist_to_mp3 {
    
    public $site_base = "https://www.youtube.com";
    
    private $log_handle;
    private $logfile = "downloaded.txt";
    
    public $playlist_link_or_file;
    
    function __construct() {
    }
    public function download($playlist_url) {
        print("\nparsing $playlist_url\n");
        
        $not_yet_downloaded=  $this->not_yet_downloaded($playlist_url);
        
        print("%s video urls found, %d downloaded already the playlist may contain more!\n");

        foreach($download_list as $index=>$video) { // over each a node containing video
            $title = $video['title'];
            $video_url = $video['url'];
            
            $video_index = $index+1;
            print("$video_index/$total_video_available# downloading $title mp3 from $video_url\n");
            
            $this->download_audio($video_url);
            $this->log_downloaded($video_url);
        }
    }
    /*
     *
     * download the mp3 usging youtube-dl with avtools installed(not yet tested for ffmpeg)
     * save the mp3 in current directory(done by youtube-dl)
     * 
     */
    static function download_audio($video_link) {
        $command = "youtube-dl --extract-audio --audio-format mp3 --audio-quality 0 $video_link";
        $proc = popen($command, 'r');
        while(!feof($proc)) {
            echo fread($proc, 4096);
            @flush();
        }
        return;
    }
    /*
     *
     * parse the given playlist or file and build youtube video list
     * @return array of videos, each element is an array having element['title'] and element['url']
     *
     */
    function build_download_list($playlist_url) {
        $download_list = array();
        $html = file_get_html($playlist_url);
        $video_links_array = $html->find('a.pl-video-title-link');
        
        $download_list = array_map(function($a) {
            $title = trim($a->plaintext);
            $href = $a->href;
            $video_url = $this->site_base.$href;
            
            return array(
                'title'=>$title,
                'url'=>$video_url
            );
        }, $video_links_array);
        return $download_list;
    }
    /*
     * not yet downloaded with respect to the mp3 existing in the filesystem
     */
    public function not_yet_downloaded_wrt_fs($playlist_url, $dir='./') {
        $videos_available = $this->build_download_list($playlist_url);
        
        $downloaded = $this->list_mp3_fs($dir);
        
        $not_yet_downloaded = array_filter($videos_available, function($video) {
            $title = $video['title'];
            if (!in_array($title, $downloaded)) {
                return true;
            } else {
                return false;
            }
        });
        
        return $not_yet_downloaded;
    }
    /*
     *
     * build the list of videos not yet downloaded by comparing the video files from a link or page to the video urls logged in logfile
     * @return array
     */
    public function not_yet_downloaded_wrt_log($playlist_url) {
        $videos_available = $this->build_download_list($playlist_url);
        
        $downloaded = $this->list_downloaded();
        
        $not_yet_downloaded = array_filter($videos_available, function($video) {
            $video_url = $video['url'];
            if (!in_array($video_url, $downloaded)) {
                return true;
            } else {
                return false;
            }
        });
        
        return $not_yet_downloaded;
    }
    /*
     *
     * list mp3 files from a directory or current directory
     * @return array, filepath as key and filename(excluding extension) as value
     * 
     */
    public function list_mp3_fs($dir='./') {
        if (!$dir) {
            $dir = realpath('./');
        }
        
        $Dir = new directoryiterator($dir);
        
        $mp3_in_fs = array();
        
        foreach($Dir as $item) {
            if ($item->isFile() && ($ext = $item->getExtension()) && (strtolower($ext)=='mp3')) {
                $title = $item->getBasename($ext);
                $path = $item->getPath();
                $mp3_in_fs[$path] = trim($title);
            }
        }
        
        return $mp3_in_fs;
    }
    /*
     *
     * build the list of already downlaoded songs from the logfile
     * @return array of absolute urls to video
     * 
     */
    public function list_downloaded() {
        if (!file_exists($this->logfile)) {
            trigger_error("file ".$this->logfile." doesnt exist", E_USER_WARNING);
            return array();
        }
        $logs = file_get_contents($this->logfile);
        $downloaded_urls = preg_split('/[\s]+/',$logs);
        $urls = array_map(function($url) {
            return trim($url);
        }, $downloaded_urls);
        
        return $urls;
    }
    /*
     * append a video url to the downlaoded logfile
     * this is performed by the system
     */
    private function log_downloaded($video_url) {
        if (!$this->log_handle) {
            $this->log_handle = fopen($this->logfile, "a");
        }
        fwrite($this->log_handle, "\n$video_url");
    }
    function __destruct() {
        if ($this->log_handle) fclose($this->log_handle);
    }
}
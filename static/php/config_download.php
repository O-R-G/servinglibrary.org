<?
$downloadURLString = getenv("MYSQL_DOWNLOAD_DATABASE_URL");

function db_connect_download() {
    global $downloadURLString;
    if( $downloadURLString )
    {
        $urlDownload = parse_url($downloadURLString);
        $host = $urlDownload["host"];
        $dbse = substr($urlDownload["path"], 1);
        $user = $urlDownload["user"];
        $pass = $urlDownload["pass"];
    }
    else
    {
        $host = "localhost";
        $dbse = "servinglibrary_download_local";
        $user = 'root';
        $pass = 'f3f4p4ax';
    }
    $db = new mysqli($host, $user, $pass, $dbse);
    if($db->connect_error)
        echo "Failed to connect to MySQL: " . $db->connect_error;
    return $db;
}


?>
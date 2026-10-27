<?php
$announcement_ver = $_GET['banner-ver'] ?? 0;

function getAnnouncement(){
    global $db;
    $sql = "SELECT o.body, o.notes FROM objects o JOIN wires w ON w.toid = o.id AND w.active = 1 AND w.fromid = 0 WHERE o.url = 'announcement' AND o.active = 1";
    $result = $db->query($sql)->fetch_assoc();
    if(!$result || !$result['body'] || fieldIsEmpty($result['body']) ) return false;
    // $meta = getMeta($result['notes']);
    // $color = $meta['color'] ?? '';
    // $color = strtolower($color);
    return ['body' => $result['body'], 'color' => 'default' ];
}
$announcement = getAnnouncement();
$announcementCookieName = 'servinglibrary_announcement_dismissed';

if($announcement !== false):
    /*
        hashing the content (rather than a flat dismissed=true/false) means an edit to
        the banner naturally invalidates any previous dismissal, since the stored hash
        no longer matches -- no separate version number to remember to bump.
    */
    $announcement_body = $announcement['body'];
    $announcementHash = md5($announcement_body);
    $dismissed = isset($_COOKIE[$announcementCookieName]) && $_COOKIE[$announcementCookieName] === $announcementHash;
?>
<div id="announcement" class="<?php echo $dismissed ? 'hidden' : ''; ?>">
    <div id="announcement-content"><?php echo $announcement_body; ?></div>
    <div id="announcement-control">
        <button id="announcement-close-btn" class="btn" aria-label="Close announcement"></button>
    </div>
</div>
<div id="announcement-background" class="fullscreen-background">
<script>
    function closeAnnouncement(){
        // announcement.hidden = true;
        announcement.classList.add('hidden');
        document.documentElement.classList.remove('frozen');
        /*
            read server-side (see $_COOKIE check above) so the banner doesn't render
            at all on the next page load once dismissed -- no flash-then-hide. Written
            directly here rather than via cookie.js's createCookie()/readCookie(),
            since that script only loads on /apply pages and this banner isn't limited
            to those.
        */
        var maxAgeSeconds = 60 * 60 * 24 * 365;
        document.cookie = '<?php echo $announcementCookieName; ?>=<?php echo $announcementHash; ?>; path=/; max-age=' + maxAgeSeconds + '; SameSite=Lax';
    }
    const announcement = document.getElementById('announcement');
    const announcement_background = document.getElementById('announcement-background');
    document.getElementById('announcement-close-btn').addEventListener('click', ()=>{
        closeAnnouncement();
    });
    if(announcement_background) {
        announcement_background.addEventListener('click', ()=>{
            closeAnnouncement();
        });
    }
    
    const disabledOptions = document.querySelectorAll('#currencyOption-gbp, #currencyOption-eur');
    console.log(disabledOptions);
    for(const option of disabledOptions) {
        
        option.addEventListener('click', ()=>{
            document.documentElement.classList.add('frozen');
            announcement.classList.remove('hidden');
        });
    }
</script>
<?php
endif;
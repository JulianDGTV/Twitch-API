<?php
require_once './TwitchAPI.php';

$twitchapi = new TwitchAPI;
$twitchapi->setClientID('');
$twitchapi->setClientSecret('');
$twitchapi->setRedirectURL('');

if (isset($_GET['code'])) {
    echo "Authorization Code: " . $twitchapi->handleCallback();
    echo '<hr /><br />';
    echo '<pre>' . print_r($twitchapi->getUserData(), true) . '</pre><br /><hr />';
}
?>
<a href="<?php echo $twitchapi->getLoginURL(); ?>">Login with Twitch</a>
<?php
/*******************************************

phpFlickr authentication tool v1.0
created by Dan Coulter (http://www.dancoulter.com)
for use with phpFlickr (http://www.phpflickr.com).

This tool allows you to get an authentication token that you can use with your 
phpFlickr install to allow a certain kind of access to one Flickr account 
through the API to anyone who visits your website.  In other words...You can 
create a photo gallery on your website that shows photos you've marked as 
private and visitors to your website won't have to do any sort of 
authentication to Flickr.

This tool is free for you to use and matches the code used at 
http://www.phpflickr.com/tools/auth/ exactly.  You may not use this to collect
other users' login information without their permission or knowledge.

This file is packaged with a full distribution of phpFlickr v1.5 that you may use
in your application.

*******************************************/

require_once(dirname(dirname(__FILE__)) . "/vendors/phpFlickr-3.1/phpFlickr.php");

if (!empty($_SESSION['api_key'])) {

    $f = new phpFlickr($_SESSION['api_key'], $_SESSION['secret'], true);

    $f->auth($_SESSION['perms']);

    $token = $f->auth_checkToken($_SESSION['phpFlickr_auth_token']);
    echo "<h2>" . $_SESSION['phpFlickr_auth_token'] . "</h2>";
    echo "This code will allow you to publish photos as the Flickr user '{$token['user']['username']}' with '{$token['perms']}' permissions";
    exit;
} elseif (!empty($_POST['api_key'])) {
    if (!empty($_POST['secret'])) {
        unset($_SESSION['phpFlickr_auth_token']);
        $_SESSION['api_key'] = $_POST['api_key'];
        $_SESSION['secret'] = $_POST['secret'];
        $_SESSION['perms'] = $_POST['perms'];
        $f = new phpFlickr($_SESSION['api_key'], $_SESSION['secret']);
        $f->auth($_SESSION['perms']);
        echo "Redirecting...";
        exit;
    } else {
        echo "<span style='color: red'>You must enter a \"secret\"</span>";
    }
} 

unset($_SESSION['phpFlickr_auth_token']);
unset($_SESSION['api_key']);

?>
    <table border="0" align="center" width="600"><tr><td>
        To authenticate your application to one user for any and all visitors to your 
        website, you'll need go through the following steps:
        <ol>
            <li>Generate an API Key with Flickr: <a href="http://www.flickr.com/services/api/key.gne">Link</a></li>
            <li>Setup authentication information : <a href="http://www.flickr.com/services/api/registered_keys.gne">Link</a></li>
            <ol type="A">
                <li>Be sure to note your API Key and Secret.  You'll need these in a moment</li>
                <li>Set the callback url to: http://<?php echo $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']); ?>/auth.php</li>
            </ol>
            <li>Fill out the form below.  You will be redirected to Flickr for authentication. Or, you can go <a href="http://phpflickr.com/tools/auth.zip">download it</a> and install and run it on your own server.</li>
        </ol>
        
        <br />
        
        <form style="text-align: center" method='post'>
            API Key<br />
            <input type="text" style="text-align: center" name="api_key" size="40" /><br />
            Secret<br />
            <input type="text" style="text-align: center" name="secret" size="40" /><br />
			<input type="hidden" name="perms" value="write" />
            <input type="submit" value="Submit">
        </form>
    </td></tr></table>
<?

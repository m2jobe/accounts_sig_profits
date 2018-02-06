<?php
require 'vendor/autoload.php';

$provider = new \Discord\OAuth\Discord([
	'clientId'     => '403626994736300042',
	'clientSecret' => 'gHkZUgqIpcyVyae0kUq9BrXnmZwfKzfU',
	'redirectUri'  => 'https://accounts.signalprofits.com/discord.php',
]);

if (! isset($_GET['code'])) {
	echo '<a href="'.$provider->getAuthorizationUrl(['scope' => ['identify', 'guilds.join', 'email']]).'">Login with Discord</a>';
} else {
	//echo $_GET['code'];

	$token = $provider->getAccessToken('authorization_code', [
		'code' => $_GET['code'],
	]);


	$user = $provider->getResourceOwner($token);
	echo $user->id;



	shell_exec('curl -v -H "Authorization: Bot NDAzNjI2OTk0NzM2MzAwMDQy.DUpk2A.iYTvp-GtHQinw-20-kpK0YW18Sk" -H "User-Agent: myBotThing (http://some.url, v0.1)" -H "Content-Type: application/json" -X PUT -d \'{"access_token":"'.$token.'"}\' https://discordapp.com/api/guilds/405093333648539649/members/'.$user->id);

}

/*


curl -v \
-H "Authorization: Bot NDAzNjI2OTk0NzM2MzAwMDQy.DUpk2A.iYTvp-GtHQinw-20-kpK0YW18Sk" \
-H "User-Agent: myBotThing (http://some.url, v0.1)" \
-H "Content-Type: application/json" \
-X PUT \
-d '{"access_token":"5Yee9rdQBYK5nYAQT2GJZJIAInxdCM"}' \
https://discordapp.com/api/guilds/405093333648539649/members/405093728731004928 
*/
?>


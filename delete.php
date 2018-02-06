<?php
include 'vendor/autoload.php';

use RestCord\DiscordClient;

$discord = new DiscordClient(['token' => 'NDAzNjI2OTk0NzM2MzAwMDQy.DU_spQ._PWEJIctG7SXkkgk0b4UIC8TE8w']); // Token is required

var_dump($discord->guild->removeGuildMember(['guild.id' => 405093333648539649, 'user.id' => 405093728731004928]));
var_dump($discord->channel->createMessage(['channel.id' => 405093333648539651, 'content' => 'Foo Bar Baz']));
?>


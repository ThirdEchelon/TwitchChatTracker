<?php

    include('db.php');

    while (true) {
        $users = [];
        $req = $bdd->query("SELECT * FROM users ORDER BY name ASC");
        while ($row = $req->fetch()) {
            $users[] = $row['name'];
        }
        $req->closeCursor();

        foreach ($users as $user){
            $data = json_decode(file_get_contents("https://tmi.twitch.tv/group/user/".$user."/chatters"), true);
            $chatter_count = $data['chatter_count'];
            $chatters = $data['chatters'];
            foreach ($chatters as $chatter){
                foreach ($chatter as $chat){
                    if (in_array($chat, $users) && $chat != $user){
                        $date = date('Y-m-d');
                        $time = date('H:i:s');
                        $req = $bdd->prepare('INSERT INTO data (id, date, time, user, channel, viewers) VALUES (DEFAULT, :date, :time, :user, :channel, :viewers)');
			            $req->execute(array('date' => $date, 'time' => $time, 'user' => $chat, 'channel' => $user, 'viewers' => $chatter_count));
                    }
                }
            }
        }
        sleep(1);
    }
?>
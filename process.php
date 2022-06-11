<!-- MIT License

Copyright (c) 2022 3rd Echelon

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE. -->

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
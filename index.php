<?php include('db.php'); ?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=320"/>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link rel="icon" type="image/png" sizes="32x32" href="img/twitch_logo.png">
        <title>Twitch Chat Tracker</title>
    </head>
    <style>	
        @font-face {
            font-family: 'TwitchFont';
            src: url('ttf/TwitchFont.ttf');
        }

        .title {
            font-family: TwitchFont;
            color: #9046ff;
            text-indent: 1%;
            font-size: 30px;
            height: 8%;
        }
        
        .content {
            padding: 10px;
        }
        
        .filter {
            width: 15%;
            float: left;
            padding: 10px;
        }

        .users {
            width: 15%;
            height: 90%;
            float: left;
            padding: 10px;
            overflow-y: scroll;
        }
        
        .data {
            width: 70%;
            height: 90%;
            float: left;
            padding: 10px;
            overflow-y: scroll;
        }
    </style>
    <body>
        <div class="content">
            <a href='index.php' style="text-decoration:none;"><div class="title">Twitch Chat Tracker</div></a>
            <div class="filter">
                <form action="index.php" method='post'>
                    <input type="text" name="date" class="form-control" placeholder="Date (yyyy-mm-dd)">
                    <br><input type="text" name="time" class="form-control" placeholder="Time (hh:mm:ss)">
                    <br><input type="text" name="user" class="form-control" placeholder="User">
                    <br><input type="text" name="channel" class="form-control" placeholder="Channel">
                    <br><button type="submit" class="btn btn-success">Search</button>
                </form>
                <hr>
                <form action="index.php" method='post'>
                    <input type="text" name="add" class="form-control" placeholder="User">
                    <br><button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
            <div class="users">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Users</th>
                        </tr>
                    </thead>
                    <tbody><?php		
                    if ($_POST['add'] != ""){
                        $req = $bdd->prepare('INSERT IGNORE users (id, name) VALUES (DEFAULT, :name)');
			            $req->execute(array('name' => strtolower($_POST['add'])));
                    }
                    $req = $bdd->query("SELECT * FROM users ORDER BY name ASC");
                    while ($data = $req->fetch())
                    { ?>
                        
                        <tr>
                            <td> 
                                <a href="index.php?user=<?php echo $data['name'] ?>"><img src="img/eye_btn.png" width="16" height="16"></a>
                                <a href="index.php?channel=<?php echo $data['name'] ?>"><img src="img/twitch_logo.png" width="16" height="16"></a>
                                <?php echo $data['name'] ?>

                            </td>
                        </tr><?php
                    }
                    $req->closeCursor(); ?>
                    
                    </tbody>
                </table>
            </div>
            <div class="data">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>User</th>
                            <th>Channel</th>
                            <th>Viewers</th>
                        </tr>
                    </thead>
                    <tbody><?php
                    $request = FALSE;
                    if ($_GET['user'] != ""){
                        $req = $bdd->prepare("SELECT * FROM data WHERE user = ? ORDER BY id DESC");
                        $req->execute([$_GET['user']]);
                        $request = TRUE;
                    }
                    elseif ($_GET['channel'] != ""){
                        $req = $bdd->prepare("SELECT * FROM data WHERE channel = ? ORDER BY id DESC");
                        $req->execute([$_GET['channel']]);
                        $request = TRUE;
                    }
                    elseif ($_POST['date'] != "" OR $_POST['time'] != "" OR $_POST['user'] != "" OR $_POST['channel'] != ""){
                        $req = $bdd->prepare("SELECT * FROM data WHERE date LIKE ? AND time LIKE ? AND user LIKE ? AND channel LIKE ? ORDER BY id DESC");
                        $req->execute(["%".$_POST['date']."%", "%".$_POST['time']."%", "%".$_POST['user']."%", "%".$_POST['channel']."%"]);
                        $request = TRUE;
                    }
                    else{
                        $req = $bdd->prepare("SELECT * FROM data WHERE date LIKE ? ORDER BY id DESC");
                        $req->execute(["%".date("Y-m-d")."%"]);
                        $request = TRUE;
                    }
                    if ($request) {

                        while ($data = $req->fetch())
                        { ?>
                            
                        <tr>
                            <td><?php echo $data['date'] ?></td>
                            <td><?php echo $data['time'] ?></td>
                            <td><?php echo $data['user'] ?></td>
                            <td><?php echo $data['channel'] ?></td>
                            <td><?php echo $data['viewers'] ?></td>
                        </tr><?php
                        }
                        $req->closeCursor();
                    }
                    ?>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
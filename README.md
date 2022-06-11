![title](https://user-images.githubusercontent.com/10144090/173142131-1bc82e23-1ae0-46dd-be67-2a3bc8d7b537.png)

This app allows to monitor the activity of Twitch users.

## How to install it ?
1. Install a LAMP server
2. Clone the project into the `/var/html/www` directory of your web server
3. Create a file named `db.php` in the same directory with the following content:
```
<?php
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=TwitchChatTracker', '<USERNAME>', '<PASSWORD>');
    }
    catch(Exception $e) {
        die('Erreur : '.$e->getMessage());
    }
?>
```
> Replace the `<USERNAME>` and `<PASSWORD>` fields with the credentials of your database server
4. Use the `sql/structure.sql` file to build the application database
5. Run the `process.php` page with the following command:
```
php /var/html/www/process.php
```
> You can use 'screen' app to make a persistent background job
6. Use the `index.php` page to add and monitor users

HAVE FUN ! 😜

## Screenshot
![screenshot](https://user-images.githubusercontent.com/10144090/173125418-4f5fe917-4e13-4ae6-a904-b1f835cf45e3.png)
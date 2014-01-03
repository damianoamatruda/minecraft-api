MinecraftAPI
============

Minecraft API written in PHP5

###Don't don't show this API as your own

Table of Contents
-----------------
1.  [Getting Started](#getting-started)
2.  [MineClient](#mineclient)
3.  [MineServer](#mineserver)
4.  [Contributing](#contributing)

Getting Started
---------------
To use it, you need to include it:

    require_once('minecraft.php');

Then include the class you need:
* [MineClient](#mineclient) or
* [MineServer](#mineserver)

The super class is called **Minecraft**.

* **request($url, $data, $method = 'get')** sends a curl request to any page and returns the result.
  * **$url** is the requested url
  * **$data** is the data to send
  * **$method** is the method to send the request

MineClient
----------
MineClient is the class to manage client actions

If you want, you can set a custom host:

     $client = new MineClient('myhost.com');

In this case,

* **$client** is the variable assigned to the class
* **'myhost.com'** is the custom host to use (If you don't set it, it will be automatically minecraft's original host)

###Functions
* **login($username, $password, $version = 14)** lets the user to log in
  * **$username** is the username
  * **$password** is the password
  * **$version** is the version
* **keepAlive()** keeps the session
* **joinServer($server_id)** lets the user to join a server
  * **$server_id** is the id of the server to join

###Sample usage
**Login with a Minecraft account**:

    if (isset($_POST['username']) && isset($_POST['password']))
    {
    	require_once('minecraft.php');
    	$auth = new MineClient;
    	if ($auth->login($_POST['username'], $_POST['password'])) echo '<h2>You are logged in as '.$auth->username.'.</h2>';
    	else
    	{
    		header('Refresh: 5');
    		echo 'Wrong username or password!';
    	}
    }
    else echo '<h1>Login with your minecraft account</h1>
    <form method="post"> 
    	<input type="text" name="username" placeHolder="Username/Email">
    	<input type="password" name="password" placeHolder="Password">
    	<input type="submit" value="Login"> 
    </form>';


**Join a Minecraft server**:

     require_once('minecraft.php');
     $user = new MineClient;
     $user->login('testusername', 'testpassword');
     if ($user->joinServer(1081)) echo 'Server with id 1081 joined!';

MineServer
----------
MineServer is the class to manage server actions

To use it, you must declare the server's id and, if you want, you can set a custom host:

     $server = new MineServer(1081, 'myhost.com');

In this case,

* **$server** is the variable assigned to the class
* **1081** is the server's id
* **'myhost.com'** is the custom host to use (If you don't set it, it will be automatically minecraft's original host)

###Functions
* **checkUser($username)** checks if a user is in the server
  * **$username** is the username to check

###Sample usage
**Check if a user is in a server**:

     require_once('minecraft.php');
     $server = new MineServer(1081);
     echo 'testusername is';
     if (!$server->checkUser('testusername')) echo ' not';
     echo ' present in the server with id 1081';

Contributing
------------
If you want to help developing the editor there are a few things you'll need to:
* **Comment Everything** - Comments help everyone understand what you did, how, and why. Please try to leave useful comments and keep with the commenting style of the rest of the project.
* **Create Issues** - If you find a bug, or if you have an idea for a feature, please create a GitHub Issue for it.  This will help us keep track of everything that has to be maintained and to evolve the project.
* **Update Documentation** - If you find an area of the documentation that is hard to follow or is misleading please add to or refine the document.  Also make sure to update documentation for anything you change or add.
* **Keep Simple Codes** - Simple code is preferred over complex and convoluted code. This is simple to follow, meaningful names for variables and functions. Write codes as small as possible. We prefer the shortcuts.

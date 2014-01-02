<?php
/*
 *	Minecraft API
 *	Author: DamiHack
 *	Website: http://github.com/DamiHack/MineAuth
 *
 */

abstract class Minecraft
{
	protected $host = 'minecraft.net',
	$text =
	[
		'error_connect'		=> 'Couldn\t connect to minecraft.net',
		'error_notloggedin'	=> 'User not logged in'
	];

	protected function request($url, $data, $method = 'get')
	{
		if (empty($url) || !is_array($data)) return false;
		if ($method != 'get' && $method != 'post') $method = 'get';
		if ($method == 'get' && !empty($data)) $url .= '?'.http_build_query($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, 'Java/1.6.0_26');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($method == 'post')
		{
			curl_setopt($ch, CURLOPT_POST, true);
			if (!empty($data)) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		}
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
}

class MineClient extends Minecraft
{
	public $username, $uid, $logged_in = false;
	private $login_name, $session_id;

	function __construct($host = null)
	{
		if (!empty($host) && filter_var($url, FILTER_VALIDATE_URL) !== FALSE) $this->host = $host;
		foreach ($this->text as &$string)
		{
			$string = '<br>'.$string.'!';
		}
	}

	function login($username, $password, $version = 69)
	{
		if (empty($username) || empty($password)) $this->logged_in = false;
		else
		{
			$response = $this->request('http://login.'.$this->host, [
				'user'		=> $username,
				'password'	=> $password,
				'version'	=> $version
			], 'post');
			if (strpos($response, ':') !== false)
			{
				$data = explode(':', $response);
				$this->logged_in	= true;
				$this->login_name	= $username;
				$this->username		= $data[2];
				$this->session_id	= $data[3];
				$this->uid			= $data[4];
			}
			else
			{
				$this->logged_in = false;
				if ($response == 'Bad login') return false;
				else trigger_error(htmlentities($response), E_USER_NOTICE);
			}
		}
		return $this->logged_in;
	}

	function keepAlive()
	{
		if ($this->logged_in)
		{
			$response = $this->request('http://login.'.$this->host.'/session', [
				'name'		=> $this->username,
				'session'	=> $this->session_id
			]);
			if (empty($response))
			{
				die($this->text['error_connect']);
				return false;
			}
			else return true;
		}
		else
		{
			die($this->text['error_notloggedin']);
			return false;
		}
	}

	function joinServer($server_id)
	{
		if ($this->logged_in)
		{
			$response = $this->request('http://session.'.$this->host.'/game/joinserver.jsp', [
				'user'		=> $this->username,
				'sessionId'	=> $this->session_id,
				'$serverId'	=> $server_id
			]);
			if ($page == 'OK') return true;
			else
			{
				die($this->text['error_connect']);
				return false;
			}
		}
		else
		{
			die($this->text['error_notloggedin']);
			return false;
		}
	}
}

class MineServer extends Minecraft
{
	protected $server_id;

	function __construct($id, $host = null)
	{
		$this->server_id = $id;
		if (!empty($host) && filter_var($url, FILTER_VALIDATE_URL) !== FALSE) $this->host = $host;
	}

	function checkUser($username)
	{ 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://session.'.$this->host.'/game/checkserver.jsp?user='.$username.'&serverId='.$this->server_id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$page = curl_exec($ch);
		curl_close($ch);
		return $page == 'YES';
	}
}

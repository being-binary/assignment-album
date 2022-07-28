<?php

	class Album{

		var $host;
		var $username;
		var $password;
		var $database;
		var $connect;
		var $query;
		var $data;
		var $home_page;
		var $filedata;
		function __construct()
		{
			$this->host = 'localhost';
			$this->username = 'root';
			$this->password = '';
			$this->database = 'album';
			$this->home_page = 'http://localhost/album/user/';
			$this->connect = new PDO("mysql:host=$this->host; dbname=$this->database", "$this->username", "$this->password");

			session_start();
		}
		
		function clean_data($data)
		{
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		function execute_query()
		{
			$this->statement = $this->connect->prepare($this->query);
			$this->statement->execute($this->data);
		}

		function total_row()
		{
			$this->execute_query();
			return $this->statement->rowCount();
		}

		function query_result()
		{
			$this->execute_query();
			return $this->statement->fetchAll();
		}

		function redirect($page)
		{
			header('location:'.$page.'');
			exit;
		}

		function admin_session_private()
		{
			if(!isset($_SESSION['admin_id']))
			{
				$this->redirect('login.php');
			}
		}

		function admin_session_public()
		{
			if(isset($_SESSION['admin_id']))
			{
				$this->redirect('index.php');
			}
		}


		function user_session_private()
		{
			if(!isset($_SESSION['user_id']))
			{
				$this->redirect('login.php');
			}
		}

		function user_session_public()
		{
			if(isset($_SESSION['user_id']))
			{
				$this->redirect('index.php');
			}
		}

	}



?>
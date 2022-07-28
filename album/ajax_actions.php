<?php
	include('php/album.php');

	$album = new Album;

	if(isset($_POST['page'])){

		if($_POST['action'] == 'check_email')
		{
			$album->query = "
			SELECT * FROM admin_table 
			WHERE admin_email_address = '".trim($_POST["email"])."'
			";

			$total_row = $album->total_row();

			if($total_row == 0)
			{
				$output = array(
					'success'	=>	true
				);

				echo json_encode($output);
			}
		}
		
		if($_POST['page'] == 'login')
		{
			if($_POST['action'] == 'login')
			{
				$album->data = array(
					':admin_email_address'	=>	$_POST['admin_email_address']
				);

				$album->query = " SELECT * FROM admin_table WHERE admin_email_address = :admin_email_address ";

				$total_row = $album->total_row();

				if($total_row > 0)
				{
					$result = $album->query_result();

					foreach($result as $row)
					{
						if(password_verify($_POST['admin_password'], $row['admin_password']))
						{
							$_SESSION['admin_id'] = $row['admin_id'];
							$output = array(
								'success'	=>	true
							);
						}
						else
						{
							$output = array(
								'error'	=>	'Wrong Password'
							);
						}
					}
				}
				else
				{
					$output = array(
						'error'		=>	'Wrong Email Address'
					);
				}
				echo json_encode($output);
			}

		}

		if($_POST['page'] == 'index'){
			if($_POST['action'] == 'add'){

				$album->data = array(
					':title'	 =>	$album->clean_data($_POST['title']),
					':description'	 =>	$_POST['description'],
					':ispublished'   =>	$_POST['ispublished'],
					':ispremium'	 =>	$_POST['ispremium']
				);
			
				$album->query = "
					INSERT INTO album 
					(title, description, assigned, premium) 
					VALUES (:title, :description, :ispublished, :ispremium)
				";

				$album->execute_query();

				$output = array(
					'success'	=>	'New Details Added'
				);

				echo json_encode($output);
			}
		}

		if($_POST['page'] == 'display_list'){

			if($_POST['action'] == 'fetch'){

				$output = array();

				$album->query = "
					SELECT * FROM album;
				";
				
				$data = array();

				$result =  $album->query_result();
				
				$total_rows = $album->total_row();

				foreach($result as $row){
					$sub_array = array();
					$sub_array[] = $row["albumID"];
					$sub_array[] = html_entity_decode($row["title"]);
					$sub_array[] = $row["description"];
					if($row["assigned"] == 1){
						$sub_array[] = 'True';
					}
					else{
						$sub_array[] = 'False';
					}
					if($row["premium"] == 1){
						$sub_array[] = 'True';
					}
					else{
						$sub_array[] = 'False';
					}
					$sub_array[] = '
							<button type="button" id="edit" class="btn btn-info btn-sm" album-id = '.$row['albumID'].'>Edit</button>
							<button type="button" id="delete" class="btn btn-info btn-sm" album-id = '.$row['albumID'].'>Delete</button>
							<button type="button" id="view" class="btn btn-info btn-sm" album-id = '.$row['albumID'].'>View</button>
							<button type="button" id="upload" class="btn btn-info btn-sm" album-id = '.$row['albumID'].'>Upload</button>
							';
					array_push($data,$sub_array);
				}

				$output = array(
					// "draw"    	  =>  intval($_POST["draw"]),
					"recordsTotal"    =>  $total_rows,
					"recordsFiltered" =>  $total_rows,
					"data" 		  =>  $data
				);
				
				echo json_encode($output);
			}
			
			if($_POST['action'] == 'delete')
			{
				$album->data = array(
					':albumId'	=>	$_POST['album_id']
				);
			
				$album->query = " DELETE FROM album WHERE albumId = :albumId ";

				$album->execute_query();

				$output = array(
					'success'	=>	'album Details has been removed'
				);

				echo json_encode($output);
			}

			if($_POST['action'] == 'edit_fetch')
			{
				$album->query = "SELECT * FROM album WHERE albumId = '".$_POST["album_id"]."'";

				$result = $album->query_result();

				foreach($result as $row)
				{
					$output['title'] = $row['title'];

					$output['description'] = $row['description'];

					$output['ispublished'] = $row['assigned'];

					$output['ispremium'] = $row['premium'];

				}

				echo json_encode($output);
			}

			if($_POST['action'] == 'Edit')
			{
				$album->data = array(
					':title'		=>	$album->clean_data($_POST['title']),
					':description'		=>	$_POST['description'],
					':ispublished'		=>	$_POST['ispublished'],
					':ispremium'		=>	$_POST['ispremium'],
					':album_id'		=>	$_POST['add_album_id']
				);
				$album->query = " UPDATE album SET title = :title, description = :description, assigned = :ispublished, premium = :ispremium WHERE albumId = :album_id";

				$album->execute_query($album->data);

				$output = array(
					'success'	=>	'album Details has been changed'
				);

				echo json_encode($output);
			}
			
			if($_POST['action'] == "load"){
				$album->query = " SELECT * FROM pictures WHERE albumID = ".$_POST['album_id']."";

				$result =  $album->query_result();
				$total_rows = $album->total_row();
				$data = array();
				foreach($result as $row){
					$sub_array = array();
					$sub_array[] = $row["title"];
					array_push($data,$sub_array);
				}
						
				echo json_encode($data);
			}

		}

	}


?>
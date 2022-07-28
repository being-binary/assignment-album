<?php

//user_ajax_action.php

include('../php/album.php');

$album = new Album;

if(isset($_POST['page']))
{

	
	if($_POST['page'] == 'login')
	{
		if($_POST['action'] == 'login')
		{
			$album->data = array(
				':user_email_address'	=>	$_POST['user_email_address']
			);

			$album->query = "
			SELECT * FROM user_table 
			WHERE user_email_address = :user_email_address
			";

			$total_row = $album->total_row();

			if($total_row > 0)
			{
				$result = $album->query_result();

				foreach($result as $row)
				{
				
					if(password_verify($_POST['user_password'], $row['user_password']))
					{
						$_SESSION['user_id'] = $row['user_id'];

						$output = array(
							'success'	=>	true
						);
					}
					else
					{
						$output = array(
							'error'		=>	'Wrong Password'
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

	if($_POST['page'] == 'display_list'){

		if($_POST['action'] == 'fetch'){

			$output = array();

			$album->query = "
				SELECT * FROM album WHERE assigned = 1;
			";
			
			$data = array();

			$result =  $album->query_result();
			
			$total_rows = $album->total_row();

			foreach($result as $row){
				$sub_array = array();
				$sub_array[] = $row["albumID"];
				$sub_array[] = html_entity_decode($row["title"]);
				$sub_array[] = $row["description"];
				// if($row["assigned"] == 1){
				// 	$sub_array[] = 'True';
				// }
				// else{
				// 	$sub_array[] = 'False';
				// }
				// if($row["premium"] == 1){
				// 	$sub_array[] = 'True';
				// }
				// else{
				// 	$sub_array[] = 'False';
				// }
				$sub_array[] = '<button type="button" id="view" class="btn btn-info btn-sm" album-id = '.$row['albumID'].'>View</button>';
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
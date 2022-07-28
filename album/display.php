<?php 
	include('header.php');
?>
<div class="card">
<div class="card-header">
		<div class="row">
			<div class="col-md-9">
				<h3 class="panel-title">Album List</h3>
			</div>
			<div class="col-md-3" align="right">
				<button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover" id="album_data_table">
				<thead>
					<tr>
						<th>Id</th>
						<th>Title</th>
						<th>Description</th>
						<th>Is Assigned</th>
						<th>Is Published</th>	
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
</div>


<div class="modal" id="formModal">
  	<div class="modal-dialog modal-lg">
    	<form method="POST" id="add_album_form">
      		<div class="modal-content">
      			<!-- Modal Header -->
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title"></h4>
          			
        		</div>

        		<!-- Modal body -->
        		<div class="modal-body">
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Ablum Title <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="text" name="title" id="title" class="form-control" />
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Description <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
					      <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea> 
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Published:  <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
					        Yes
						<input type="radio" id="ispublished" name="ispublished" value=1>
						No
						<input type="radio" id="ispublished" name="ispublished" value=0><br>
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Premium<span class="text-danger">*</span></label>
	              			<div class="col-md-8">
					        Yes
						<input type="radio" id="ispremium" name="ispremium" value=1>
						No
						<input type="radio" id="ispremium" name="ispremium" value=0><br>
	                		</div>
            			</div>
          			</div>
        		</div>

	        	<!-- Modal footer -->
	        	<div class="modal-footer">
				<input type="hidden" name="add_album_id" id="album_id">
				<input type="hidden" name="page"id="page" value="index" />
				<input type="hidden" name="action" id="action" value="add" />
				<!-- <input type="submit" value="submit" class="btn btn-success btn-sm"> -->
				<input type="reset" value="Reset" class="btn btn-success btn-sm">
				
				<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="add" />

	          		<button type="button" class="btn btn-danger btn-sm" id="close_action_modal" data-dismiss="modal">Close</button>
	        	</div>
        	</div>
    	</form>
  	</div>
</div>

<span id="message">
<span id="message_operation"></span>
<div class="modal" id="deleteModal">
  	<div class="modal-dialog">
    	<div class="modal-content">

      		<!-- Modal Header -->
      		<div class="modal-header">
        		<h4 class="modal-title">Delete Confirmation</h4>
      		</div>

      		<!-- Modal body -->
      		<div class="modal-body">
        		<h3 align="center">Are you sure you want to remove this?</h3>
      		</div>

      		<!-- Modal footer -->
      		<div class="modal-footer">
      			<button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">OK</button>
        		<button type="button" id="close_action_comformation" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>

<div class="modal" id="viewModal">
  	<div class="modal-dialog">
    	<div class="modal-content">

      		<!-- Modal Header -->
      		<div class="modal-header">
        		<h4 class="modal-title"> View </h4>
      		</div>
      		<!-- Modal body -->
      		<div class="modal-body" id="display-Image">
      		</div>

      		<!-- Modal footer -->
      		<div class="modal-footer">
			<button type="button" id="close_action_view" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>


<div class="modal" id="UploadModal">
  	<div class="modal-dialog">
    	<div class="modal-content">
		<!-- Modal Header -->
      		<div class="modal-header">
        		<h4 class="modal-title"> Upload Photo </h4>
      		</div>
      		<!-- Modal body -->
      		<div class="modal-body">
		      <p id="msg"></p>
		      <input type="file" id="multiFiles" name="files[]" multiple="multiple"/><br>
			
      		</div>

      		<!-- Modal footer -->
      		<div class="modal-footer">
			<input type="reset" value="Reset" class="btn btn-success btn-sm">
			<button id="upload">Upload</button>
			<button type="button" id="close_action_upload" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      		</div>
		</div>
  	</div>
</div>


<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<script>
	$(document).ready(function () {
		var album_id = '';

		var dataTable = $('#album_data_table').DataTable({
			"order" : [],
			"ajax" : {
				url:"ajax_actions.php",
				type:"POST",
				data:{action:'fetch', page:'display_list'}
			},
			// "columnDefs":[
			// 	{
			// 		// "targets":[4],
			// 		"orderable":false,
			// 	},
			// ],
		});

		function reset_form()
		{
			$('#modal_title').text('Add Details');
			$('#button_action').val('add');
			$('#action').val('add');
			$('#add_album_form')[0].reset();
			$('#add_album_form').parsley().reset();
		}

		$('#add_button').click(function(){
			reset_form();
			$('#formModal').modal('show');
			$('#message_operation').html('');
		});

		$('#close_action_view').click(function(){
			$('#viewModal').modal('hide');
			$('#message_operation').html('');
		});

		$('#close_action_modal').click(function(){
			$('#formModal').modal('hide');
			$('#message_operation').html('');
		});

		$('#close_action_comformation').click(function(){
			$('#deleteModal').modal('hide');
			$('#message_operation').html('');
		});

		$(document).on('click', '#delete', function(){
			album_id = $(this).attr('album-id');
			$('#deleteModal').modal('show');
		});

		$(document).on('click', '#view', function(){
			dataTable.ajax.reload();
			document.getElementById("display-Image").innerHTML = '';
			album_id = $(this).attr('album-id');
			$('#viewModal').modal('show');
			$.ajax({
			url:"ajax_actions.php",
			method:"POST",
			data:{album_id:album_id, action:'load',page:'display_list'},
			dataType:"json",
			success:function(data)
			{
				
				for ( var i = 0; i <= data.length; i++ ){
					if(data[i] == undefined){
						continue;
					}
					var path = 'uploads/'+data[i];
					document.getElementById("display-Image").innerHTML += "<img src="+path+" width="+150+" height="+150+">";
				}				
			}
			})
		});

		$(document).on('click', '#upload', function(){
			dataTable.ajax.reload();
			album_id = $(this).attr('album-id');
			$('#UploadModal').modal('show');
		});
		
		$('#upload').on('click', function (){
			var form_data = new FormData();
			var ins = document.getElementById('multiFiles').files.length;

			for (var x = 0; x < ins; x++) {
				var name = document.getElementById("multiFiles").files[x].name;
				var ext = name.split('.').pop().toLowerCase();
				if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
				{
				alert("Invalid Image File "+name);
				}
			}

			for (var x = 0; x < ins; x++) {
				form_data.append("files[]", document.getElementById('multiFiles').files[x]);
			}
			
			form_data.append("album_id",album_id);
			form_data.append('action','upload')
			$.ajax({
				url:"upload.php",
				method:"POST",
				dataType: 'text',
				data: form_data,
				contentType: false,
				cache: false,
				processData: false,
				success: function (response) {
					$('#msg').html(response);
				}
				
			});
		});

		$('#close_action_upload').click(function(){
			$('#UploadModal').modal('hide');
			$('#message_operation').html('');
		});

		$(document).on('click', '#edit', function(){
			album_id = $(this).attr('album-id');
			$('#formModal').modal('show');
		});
	

		$('#ok_button').click(function(){
		$.ajax({
			url:"ajax_actions.php",
			method:"POST",
			data:{album_id:album_id, action:'delete',page:'display_list'},
			dataType:"json",
			success:function(data)
			{
				$('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
				$('#deleteModal').modal('hide');
				dataTable.ajax.reload();
			}
			})
		});

		$('#add_album_form').parsley();
		$('#add_album_form').on('submit',function(event){

			event.preventDefault();

			$('#title').attr('required','required');
			
			if($('#add_album_form').parsley().validate()){

				$.ajax({
					url:"ajax_actions.php",
					method:"POST",
					data:$(this).serialize(),
					dataType:"json",
					success:function(data) {
						alert(data);
						if(data.success){
							$('#message').html('<div class="Success"> Successfull </div>');
							// reset_form();

							dataTable.ajax.reload();

							$('#formModal').modal('hide');
						}
						else
						{
							$('#message').html('<div class="alert alert-danger">'+data.error+'</div>');
						}
						dataTable.ajax.reload();
					}	
				});
			}
		});


	
		$(document).on('click', '#edit', function(){
			album_id = $(this).attr('album-id');

			reset_form();

			$.ajax({
				url:"ajax_actions.php",
				method:"POST",
				data:{action:'edit_fetch', album_id:album_id, page:'display_list'},
				dataType:"json",
				success:function(data)
				{
					$('#title').val(data.title);

					$('#description').val(data.description);

					$('#album_id').val(album_id);

					$('#modal_title').text('Edit Album Details');

					$('#button_action').val('Edit');

					$('#action').val('Edit');
					$('#page').val('display_list');
					$('#formModal').modal('show');	
				}
			});
		});


	});
</script>
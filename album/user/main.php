<?php 
	include('header.php');
?>
<div class="card">
<div class="card-header">
		<div class="row">
			<div class="col-md-9">
				<h3 class="panel-title">Album List</h3>
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
						<!-- <th>Is Assigned</th>
						<th>Is Published</th>	 -->
						<th>Action</th>
					</tr>
				</thead>
			</table>
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


<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<script>
	$(document).ready(function () {
		var dataTable = $('#album_data_table').DataTable({
			"order" : [],
			"ajax" : {
				url:"user_ajax_action.php",
				type:"POST",
				data:{action:'fetch', page:'display_list'}
			},
		});
		$('#close_action_view').click(function(){	
			$('#viewModal').modal('hide');
			$('#message_operation').html('');
		});

		$(document).on('click', '#view', function(){
			album_id = $(this).attr('album-id');
			$('#viewModal').modal('show');
		});
		
		$(document).on('click', '#view', function(){
			document.getElementById("display-Image").innerHTML = '';
			dataTable.ajax.reload();
			album_id = $(this).attr('album-id');
			$('#viewModal').modal('show');
			$.ajax({
			url:"user_ajax_action.php",
			method:"POST",
			data:{album_id:album_id, action:'load',page:'display_list'},
			dataType:"json",
			success:function(data)
			{
				for ( var i = 0; i <= data.length; i++ ){
					if(data[i] == undefined){
						continue;
					}
					var path = '../uploads/'+data[i];
					document.getElementById("display-Image").innerHTML += "<img src="+path+" width="+150+" height="+150+">";
				}				
			}
			})
		});

	});
</script>
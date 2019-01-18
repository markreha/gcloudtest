@extends('layouts.appmaster') 
@section('title', 'IoT Weather Report Table') 

@section('content')
<div class="flex-center position-ref full-height">
	<!-- Page Content -->
	<div class="content">
		<!--  Title and Menu-->
		<div class="title m-b-md">Data Report</div>
		<div class="links"><a href="weather">Run Another Report</a></div>
		<br>
		<!-- Report -->
		<div class="flex-center">
			@if($data)
     			<script>
 					// Display Weather Data in a jQuery Data Table
      				function displayData()
      				{
						// Get Data from Laravel Controller
          				var json = {!!$data!!};

						// Render a Table using jQuery Data Table
      					$('#dataTable').dataTable({
       						"responsive" : true,
        					"data": json.data,
         					"columns": [{ "data": "temperature", "responsivePriority": 1 },{ "data": "pressure", "responsivePriority": 4 },{ "data": "humidity", "responsivePriority": 2 },{ "data": "date", "responsivePriority": 3 }]
         				} );      				
         			}
         			      		      	
      		      	// Get Weather Data and display using jQuery Data Table
      		        $(document).ready(displayData);
      			</script>  
      			 
 	            <!-- Weather Data Table -->
				<table id="dataTable" style="width:50%" border="1" class="display">
      				<thead>
						<tr style="background-color:#A0A0A0">
							<th><label>Temperature</label></th>
							<th><label>Pressure</label></th>
							<th><label>Humidity</label></th>
							<th><label>Date</label></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
      			</table>      	
				<br>
				<br>
			@else 
				<span style="color:red">No records found.</span> 
			@endif
		</div>
	</div>
</div>
@endsection

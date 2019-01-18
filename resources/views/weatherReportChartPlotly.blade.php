@extends('layouts.appmaster') 
@section('title', 'IoT Weather Report Chart') 

@section('content')
<div class="flex-center position-ref full-height">
	<!-- Page Content -->
	<div class="content">
		<!--  Title and Menu Links -->
		<div class="title m-b-md">Chart Report</div>
		<div class="links"><a href="weather">Run Another Report</a></div>
		<br>
		<!-- Report -->
		<div class="container-fluid" align="center">
			<div class="row">
				<div class="col-sm-12">
					<div id="weather_div">
						@if($data) 
							<script>
								// Get all Data from Laravel Controller
								var title = {!!$data['title']!!};
								var dates = {!!$data['dates']!!};
								var temperature = {!!$data['temperature']!!};
								var pressure = {!!$data['pressure']!!};
								var humidity = {!!$data['humidity']!!};

								// Render a Line Chart using Plotly
    							(function() 
    	    					 {
    								var d3 = Plotly.d3;    
    								var WIDTH_IN_PERCENT_OF_PARENT = 100;
    								var HEIGHT_IN_PERCENT_OF_PARENT = 70;
    								var gd3 = d3.select('div#weather_div')
    								    				.append('div')
    								    				.style(
    	    								    				{
        								    						width: WIDTH_IN_PERCENT_OF_PARENT + '%', 'margin-left': 0 + 'px',
    								        						height: HEIGHT_IN_PERCENT_OF_PARENT + 'vh', 'margin-top': 0 + 'vh'
    								    						}
    								    					  );
    								var gd = gd3.node();

    								var layout = {
    										  		title: title,
    										  		legend: {"orientation": "h"},
    										  		margin: { l: 20, r: 20, b: 75, t: 50, pad: 0}
    											 };
    								var trace1 = {
    										  		x: dates,
    										  		y: temperature,
    										  		mode: 'lines+markers',
    										  	  	name: 'Temperature'
    											 };
    								var trace2 = {
									  		x: dates,
									  		y: pressure,
									  		mode: 'lines+markers',
									  	  	name: 'Pressure'
										 };
    								var trace3 = {
									  		x: dates,
									  		y: humidity,
									  		mode: 'lines+markers',
									  	  	name: 'Humidity'
										 };
    								var data = [ trace1, trace2, trace3 ];
    								Plotly.newPlot(gd, data, layout);
    
    								window.onresize = function() { Plotly.Plots.resize(gd); };
    							 })();
							</script>
						@else 
							<span style="color:red">No records found.</span> 
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

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
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div id="weather_div1">
						@if($lava) 
							{!! $lava->render('LineChart','Temps', 'weather_div1') !!} 
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

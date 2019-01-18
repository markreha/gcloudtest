@extends('layouts.appmaster') 
@section('title', 'IoT Weather Error Page') 

@section('content')
<div class="flex-center position-ref full-height">
	<!-- Page Content -->
	<div class="content">
		<!--  Title and Menu -->
		<div class="title m-b-md">Report Error</div>
		<div class="links"><a href="weather">Run Another Report</a></div>
		<br>
		<!-- Display Error -->
		<div>
			<span style="color:red">Error Status Code: $error.</span> 
		</div>		
	</div>
</div>
@endsection

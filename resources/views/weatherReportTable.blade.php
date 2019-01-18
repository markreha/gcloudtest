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
			@if(!empty($data['data']))
				<table style="margin: 0px auto;text-align: center;" class="table-responsive" border="1">
					<tr>
						<th width="25%" style="text-align: center;">Temperature</th>
						<th width="25%" style="text-align: center;">Pressure</th>
						<th width="25%" style="text-align: center;">Humidity</th>
						<th width="25%" style="text-align: center;">Date</th>
					</tr>
				@foreach ($data['data'] as $item)
					<tr>
						<td>{{ $item['temperature'] }}</td>
						<td>{{ $item['pressure'] }}</td>
						<td>{{ $item['humidity'] }}</td>
						<td>{{ $item['date'] }}</td>
					</tr>
				@endforeach
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

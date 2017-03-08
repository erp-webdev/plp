<!DOCTYPE html>
<html>
<head>
	<title>Job Monitoring</title>
	<meta HTTP-EQUIV="Refresh" CONTENT="1000; {{ url('/jobs') }}">
	<style type="text/css">
		table, tr, td{
			border-width: 1px;
			border-style: solid;
			border-spacing: 0px;
			padding: 5px;
		}
	</style>
</head>
<body>
<table>
	<thead>
		<th>Action</th>
		<th>id</th>
		<th>payload</th>
		<th>Attempts</th>
		<th>reserved</th>
		<th>reserved at</th>
		<th>available at</th>
		<th>created at</th>
	</thead>
	<tbody>
		@foreach($jobs as $job)
			<tr>
				<td><a href="{{ route('job.edit', [$job->id, $job->created_at]) }}">RUN NOW</a></td>
				<td>{{ $job->id }}</td>
				<td>
					<form action="{{ route('jobs.update', $job->id) }}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<textarea name="payload" cols="75" rows="5">{{ $job->payload }}</textarea>
						<button type="submit" class="btn btn-sm btn-success">Save</button>
					</form>
				</td>
				<td>{{ $job->attempts }}</td>
				<td>{{ $job->reserved }}</td>
				<td>{{ $job->reserved_at }}</td>
				<td>{{ date('Y-m-d H:i:s', $job->available_at) }}
				</td>
				<td>{{ date('Y-m-d H:i:s', $job->created_at) }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
</body>
</html>
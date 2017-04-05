@extends('admin.layouts.app')
@section('content')
<style media="screen" style="css">
.box{
	width: 200px;

}
</style>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<?php
				$greetings = '';
				 /* This sets the $time variable to the current hour in the 24 hour clock format */
				 $time = date("H");
				 /* Set the $timezone variable to become the current timezone */
				 $timezone = date("e");
				 /* If the time is less than 1200 hours, show good morning */
				 if ($time < "12") {
						 $greetings = "Good morning, ";
				 } else
				 /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
				 if ($time >= "12" && $time < "17") {
						 $greetings = "Good afternoon, ";
				 } else
				 /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
				 if ($time >= "17") {
						 $greetings = "Good evening, ";
				 } //else
				 /* Finally, show good night if the time is greater than or equal to 1900 hours */
				//  if ($time >= "19") {
				// 		 $greetings "Good night, ";
				//  }
			 ?>
			 <h2><?php echo $greetings . ucwords(strtolower(Auth::user()->name)) . "!"; ?></h2>
			 <hr>
		</div>
	</div>
	<div class="row">
		@permission(['officer', 'custodian'])
		
		@endpermission
	</div>
	<div class="row">

	</div>

@endsection
@section('scripts')
<script type="text/javascript">

</script>
@endsection

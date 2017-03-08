@extends('admin.layouts.app')
@section('content')
	<div class="col-md-12">
		<h1>Preferences</h1>
		<p>Manage System configurations, settings, and preferences.</p>
	</div>
	<div class="col-md-6">
		<form class="form form-horizontal" action="{{ route('preferences.update') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @foreach($settings as $setting)
                @if($setting->data_type == 'boolean')
                <div class="form-group">
                    <span class="col-md-5">{{ $setting->description }}</span>
                    <div class="col-md-7">
                        <input type="hidden" id="{{ $setting->name }}" name="{{ $setting->name }}" value="{{ $setting->value }}">
                        <input type="checkbox" <?php if($setting->value == '1') echo 'checked'; ?> onclick="changeValue('#{{ $setting->name }}')">
                        <span class="help-block">{{ $setting->helper }}</span>
                    </div>
                </div>
                @else
                <div class="form-group">
                    <span class="col-md-5">{{ $setting->description }}</span>
                    <div class="col-md-7">
                        <input class="form-control input-sm" type="{{ $setting->data_type }}" name="{{ $setting->name }}" value="{{ $setting->value }}">
                        <span class="help-block">{{ $setting->helper }}</span>
                    </div>
                </div>
                @endif


                
            @endforeach      
            <button class="btn btn-success btn-block btn-sm" type="submit"><i class="fa fa-save"></i> Save</button>
        </form>
	</div>
    <script type="text/javascript">
        function changeValue(name) {
            if($(name).val() == 0){
                $(name).val(1);
            }else{
                $(name).val(0);
            }
        }
    </script>
@endsection
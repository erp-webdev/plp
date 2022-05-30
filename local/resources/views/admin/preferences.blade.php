@extends('admin.layouts.app')
@section('content')
    <style type="text/css">
        .help-block{
            font-size: 10px;
        }
    </style>
	<div class="col-md-12">
		<h1>Maintenance</h1>
		<p>Manage System configurations, settings, and preferences.</p>
	</div>
	<div class="col-md-12" style="border: 1px solid #ccc; padding: 10px; margin: 5px;">
        <h4>PLP Configuration</h4>
        <hr>
		<form class="form form-horizontal" action="{{ route('preferences.update') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @foreach($settings as $setting)
                @if($setting->data_type == 'boolean')
                <div class="form-group col-md-6">
                    <span class="col-md-7">
                    {{ $setting->description }}
                    <span class="help-block">{{ $setting->helper }}</span>
                    </span>
                    <div class="col-md-5">
                        <input type="hidden" id="{{ $setting->name }}" name="{{ $setting->name }}" value="{{ $setting->value }}">
                        <input type="checkbox" <?php if($setting->value == '1') echo 'checked'; ?> onclick="changeValue('#{{ $setting->name }}')">
                    </div>
                </div>
                @else
                <div class="form-group  col-md-6">
                    <span class="col-md-7">
                        {{ $setting->description }}
                        <span class="help-block">{{ $setting->helper }}</span>
                    </span>
                    <div class="col-md-5">
                        <input class="form-control input-sm" type="{{ $setting->data_type }}" name="{{ $setting->name }}" value="{{ $setting->value }}" step="any" required>
                    </div>
                </div>
                @endif
            @endforeach      
            <button class="btn btn-success btn-block" type="submit"><i class="fa fa-save"></i> Save</button>
        </form>
	</div>
    <div class="col-md-7" style="border: 1px solid #ccc; padding: 10px; margin: 5px;">
        <h4>Terms</h4>
        <hr>
        <form class="form form-horizontal" action="{{ route('preferences.terms') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <table class="table table-hover table-condensed table-striped">
                <thead>
                    <th>Rank/Position</th>
                    <th>Minimum</th>
                    <th>Maximum</th>
                </thead>
                <tbody>
                    @foreach($terms as $term)
                    <tr>
                        <td>
                            <input type="hidden" name="id[]" value="{{ $term->id }}">
                            {{ $term->rank_position }}
                        </td>
                        <td><input class="form-control input-sm" min="0" type="number" step="500" name="min_amount[]" value="{{ $term->min_amount }}" required></td>
                        <td><input class="form-control input-sm" min="0" type="number" step="500" name="max_amount[]" value="{{ $term->max_amount }}" required></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-block btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
        </form>
    </div>
   <div class="col-md-7" style="border: 1px solid #ccc; padding: 10px; margin: 5px;">
        <h4>Guaranteed Amount</h4>
        <span class="help-block">Zero (0) means no limit</span>
        <hr>
        <form class="form form-horizontal" action="{{ route('preferences.limits') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <table class="table table-hover table-condensed table-striped">
                <thead>
                    <th>Rank/Position</th>
                    <th>Maximum</th>
                </thead>
                <tbody>
                    @foreach($limits as $limit)
                    <tr>
                        <td>
                            <input type="hidden" name="id[]" value="{{ $limit->id }}">
                            {{ utf8_encode($limit->RankDesc) }}
                        </td>
                        <td><input class="form-control input-sm" min="0" type="number" step="500" name="amount[]" value="{{ $limit->Amount }}" required></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-block btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
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
@extends('admin.layouts.app')
@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1>Treasury Transmittals</h1>
        <span>Send list of transmittals to PLP Custodian</span>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <a href="{{ route('treasury.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        <a href="{{ route('treasury.transmittal') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        
    </div>
</div>

@endsection
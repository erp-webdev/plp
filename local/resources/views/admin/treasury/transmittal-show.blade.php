@extends('admin.layouts.app')
@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1>Treasury Transmittals</h1>
        <h6>Send list of transmittals to PLP Custodian.</h6>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 form-horizontal">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <a href="{{ route('treasury.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
            <a href="{{ route('treasury.transmittal') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
        </div>
        <form action="{{ route('treasury.transmittal') }}" method="GET">
            {{ csrf_field() }}
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label class="col-xs-12 col-sm-6 col-md-4">Released date</label>
                <div class="col-xs-12 col-sm-6 col-md-8">
                    <div class="input-group">
                        <input class="form-control input-sm datepicker-range" name="check_released" placeholder="mm/dd/yyyy - mm/dd/yyyy" autocomplete="off">
                        <div class="input-group-btn">
                            <button class="btn btn-primary" type="submit" name="search"><i class="fa fa-search"></i></button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
    <hr>
        <p>The list includes all loan applications not yet transmitted. </p>
        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered table-striped table-condensed">
                <thead>
                    <th style="">Exclude</th>
                    <th style="">Company</th>
                    <th style="">Name</th>
                    <th style="">CV #</th>
                    <th style="">Check #</th>
                    <th style="">Amount</th>
                    <th style="">Check Released Date</th>
                </thead>
                <tbody>
                    @foreach($transmittals as $loan)
                    <tr>
                        <td style=""><input type="checkbox" name="excelude[]" value="{{ $loan->id }}"></td>
                        <td style="">{{ $loan->COMPANY }}</td>
                        <td style="">{{ $loan->FullName }}</td>
                        <td style="">{{ $loan->cv_no }}</td>
                        <td style="">{{ $loan->check_no }}</td>
                        <td style="">{{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
                        <td style="">{{ date('Y-m-d', strtotime($loan->released)) }}</td>
                    </tr>
                    @endforeach
                    @if(!count($transmittals))
                    <tr>
                        <td colspan="6" style="">no record for transmittal</td>
                    </tr>
                    @endif
            </table>
        </div>
    </div>
</div>

@endsection
@extends('admin.layouts.app')
@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1>Treasury Transmittals</h1>
        <h4>Send list of transmittals to PLP Custodian</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <a href="{{ route('treasury.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        <a href="{{ route('treasury.transmittal') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
    <hr>
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
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
    <hr>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered table-striped table-condensed">
                <thead>
                    <th style="border: 1px solid black; padding: 2px">Exclude</th>
                    <th style="border: 1px solid black; padding: 2px">Company</th>
                    <th style="border: 1px solid black; padding: 2px">Name</th>
                    <th style="border: 1px solid black; padding: 2px">CV #</th>
                    <th style="border: 1px solid black; padding: 2px">Check #</th>
                    <th style="border: 1px solid black; padding: 2px">Amount</th>
                    <th style="border: 1px solid black; padding: 2px">Check Released Date</th>
                </thead>
                <tbody>
                    @foreach($transmittals as $loan)
                    <tr>
                        <td style="border: 1px solid black; padding: 2px"><input type="checkbox" name="excelude[]" value="{{ $loan->id }}"></td>
                        <td style="border: 1px solid black; padding: 2px">{{ $loan->COMPANY }}</td>
                        <td style="border: 1px solid black; padding: 2px">{{ $loan->FullName }}</td>
                        <td style="border: 1px solid black; padding: 2px">{{ $loan->cv_no }}</td>
                        <td style="border: 1px solid black; padding: 2px">{{ $loan->check_no }}</td>
                        <td style="border: 1px solid black; padding: 2px">{{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
                        <td style="border: 1px solid black; padding: 2px">{{ date('Y-m-d', strtotime($loan->released)) }}</td>
                    </tr>
                    @endforeach
                    @if(!count($transmittals))
                    <tr>
                        <td colspan="6" style="border: 1px solid black; padding: 2px">no record for transmittal</td>
                    </tr>
                    @endif
            </table>
        </div>
    </div>
</div>

@endsection
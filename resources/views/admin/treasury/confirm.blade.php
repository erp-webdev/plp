@extends('admin.layouts.app')
@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1>Confirm Treasury Transmittals</h1>
        <h6>Send list of transmittals to PLP Custodian.</h6>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 form-horizontal">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <a href="{{ route('treasury.transmittal') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <form action="{{ route('treasury.transmittals.send') }}" method="post">
        {{ csrf_field() }}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <hr>
            <p>The list below includes all selected PLP applications for transmittal to PLP custodian. </p>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered table-striped table-condensed">
                    <thead>
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
                            <td style="">{{ $loan->COMPANY }}
                            <input type="hidden" name="include[]" value="{{ $loan->id }}" checked>
                            </td>
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
            <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Send Email</button>
        </div>
    </form>

</div>

@endsection
@section('scripts')
<script>
    function checkBoxes(){
        $('input[name="include[]"]').each(function (index, element) {
            $(element).prop({checked: !$(element).prop('checked')})
            
        });
    }
</script>
@endsection
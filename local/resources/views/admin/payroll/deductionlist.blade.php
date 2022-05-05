<div id="deductionList">
    <table class="table table-condensed table-striped table-hover">
        <thead>
            <th>Company</th>
            <th>Control #</th>
            <th>EmpID</th>
            <th >Employee Name</th>
            <th style="text-align: right;">Deduction</th>
            <th style="text-align: right;">Total Payable</th>
        </thead>
            <tbody>
            @foreach($empList as $emp)
                <tr class="<?php if(!empty(trim($emp->ar_no))) echo 'success'; ?> " title="<?php if(!empty(trim($emp->ar_no))) echo 'POSTED'; ?> ">
                    <td>{{ $emp->COMPANY }}</td>
                    <td>{{ $emp->ctrl_no }}</td>
                    <td>{{ $emp->EmpID }}</td>
                    <td>{{ utf8_encode($emp->FullName) }}</td>
                    <td style="text-align: right;">{{ number_format($emp->deductions, 2, '.', ',') }}</td>
                    <td style="text-align: right;">{{ number_format($emp->total, 2, '.', ',') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
 </div>



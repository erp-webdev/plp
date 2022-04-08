<div>
    <p>
        Dear FCC / TDG,
    </p>
    <p>
        May we endorse to your approval the attached personal loan application of the following employees.
    </p>
    <table class="table table-sm table-bordered">
        <tr>
            <th>Company</th>
            <th>Employee No.</th>
            <th>Employee Name</th>
            <th>Surey / Co-borrower</th>
            <th>Total Amount</th>
            <th>Payment Terms <br> (no. of mos.)</th>
            <th>Deduction per Payday</th>
        </tr>
        @foreach($loans as $loan)
        <tr>
            <td>{{ $loan->COMPANY }}</td>
            <td>{{ $loan->EmpID }}</td>
            <td>{{ $loan->FullName }}</td>
            <td>{{ $loan->guarantor_FullName }}</td>
            <td>{{ number_format($loan->total, '.', ',') }}</td>
            <td>{{ $loan->terms_month }}</td>
            <td>{{ number_format($loan->deductions) }}</td>
        </tr>
        @endforeach
    </table>
    <p>Thank you!</p>
</div>
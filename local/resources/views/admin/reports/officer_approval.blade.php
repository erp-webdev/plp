<style>
    table, tr, td {
        
    }
</style>
<div>
    <p>
        Dear FCC / TDG,
    </p>
    <p>
        May we endorse to your approval the attached personal loan application of the following employees.
    </p>
    <table class="table table-sm table-bordered">
        <tr>
            <th style="border: 1px solid black;">Company</th>
            <th style="border: 1px solid black;">Employee No.</th>
            <th style="border: 1px solid black;">Employee Name</th>
            <th style="border: 1px solid black;">Surey / Co-borrower</th>
            <th style="border: 1px solid black;">Total Amount</th>
            <th style="border: 1px solid black;">Payment Terms <br> (no. of mos.)</th>
            <th style="border: 1px solid black;">Deduction per Payday</th>
        </tr>
        @foreach($loans as $loan)
        <tr>
            <td>{{ $loan->COMPANY }}</td>
            <td>{{ $loan->EmpID }}</td>
            <td>{{ $loan->FullName }}</td>
            <td>{{ $loan->guarantor_FullName }}</td>
            <td>{{ number_format($loan->total, 2, '.', ',') }}</td>
            <td>{{ $loan->terms_month }}</td>
            <td>{{ number_format($loan->deductions, 2, '.', ',') }}</td>
        </tr>
        @endforeach
    </table>
    <p>Thank you!</p>
</div>
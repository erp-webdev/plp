<style>
    table, tr, td {
        
    }
</style>
<div style="color: black">
    <p>
        Dear FCC / TDG,
    </p>
    <p>
        May we endorse to your approval the attached personal loan application of the following employees.
    </p>
    <table style="border-collapse: collapse; color: black" class="table table-sm table-bordered">
        <tr>
            <th style="border: 1px solid black; padding: 2px">Company</th>
            <th style="border: 1px solid black; padding: 2px">Employee No.</th>
            <th style="border: 1px solid black; padding: 2px">Employee Name</th>
            <th style="border: 1px solid black; padding: 2px">Surey / Co-borrower</th>
            <th style="border: 1px solid black; padding: 2px">Total Amount</th>
            <th style="border: 1px solid black; padding: 2px">Payment Terms <br> (no. of mos.)</th>
            <th style="border: 1px solid black; padding: 2px">Deduction per Payday</th>
        </tr>
        @foreach($loans as $loan)
        <tr>
            <td style="border: 1px solid black; padding: 2px">{{ $loan->COMPANY }}</td>
            <td style="border: 1px solid black; padding: 2px">{{ $loan->EmpID }}</td>
            <td style="border: 1px solid black; padding: 2px">{{ $loan->FullName }}</td>
            <td style="border: 1px solid black; padding: 2px">{{ $loan->guarantor_FullName }}</td>
            <td style="border: 1px solid black; padding: 2px">{{ number_format($loan->total, 2, '.', ',') }}</td>
            <td style="border: 1px solid black; padding: 2px">{{ $loan->terms_month }}</td>
            <td style="border: 1px solid black; padding: 2px">{{ number_format($loan->deductions, 2, '.', ',') }}</td>
        </tr>
        @endforeach
    </table>
    <p>Thank you!</p>
</div>
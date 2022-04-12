<table style="color: black; border: collapsed">
    <thead>
        <th style="border: 1px solid black; padding: 2px">Company</th>
        <th style="border: 1px solid black; padding: 2px">Name</th>
        <th style="border: 1px solid black; padding: 2px">CV #</th>
        <th style="border: 1px solid black; padding: 2px">Check #</th>
        <th style="border: 1px solid black; padding: 2px">Amount</th>
        <th style="border: 1px solid black; padding: 2px">Check Released Date</th>
    </thead>
    <tbody>
        @foreach($loans as $loan)
        <tr>
            <td style="border: 1px solid black; padding: 2px">{{ $loan->COMPANY }}</td>
            <td style="border: 1px solid black; padding: 2px">{{ $loan->FullName }}</td>
            <td style="border: 1px solid black; padding: 2px">{{ $loan->cv_no }}</td>
            <td style="border: 1px solid black; padding: 2px">{{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
            <td style="border: 1px solid black; padding: 2px">{{ $loan->released }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
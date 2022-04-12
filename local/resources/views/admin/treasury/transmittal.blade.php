<table>
    <thead>
        <th>Company</th>
        <th>Name</th>
        <th>CV #</th>
        <th>Check #</th>
        <th>Amount</th>
        <th>Check Released Date</th>
    </thead>
    <tbody>
        @foreach($loans as $loan)
        <tr>
            <td>{{ $loan->COMPANY }}</td>
            <td>{{ $loan->FullName }}</td>
            <td>{{ $loan->cv_no }}</td>
            <td>{{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
            <td>{{ $loan->released }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

    <div id="deductionList">
           <table class="table table-condensed table-striped table-hover">
                   <thead>
                           <th>Control #</th>
                           <th>Employee ID</th>
                           <th>Employee Name</th>
                           <th>Deduction</th>
                   </thead>
                   <tbody>
                           @foreach($empList as $emp)
                            <tr>
                                    <td>{{ $emp->ctrl_no }}</td>
                                    <td>{{ $emp->EmpID }}</td>
                                    <td>{{ $emp->FullName }}</td>
                                    <td>{{ $emp->deductions }} </td>
                            </tr>
                           @endforeach
                   </tbody>
           </table>
    </div>




    <div id="deductionList">
           <table class="table table-condensed table-striped table-hover">
                   <thead>
                           <th></th>
                           <th>Control #</th>
                           <th>EmpID</th>
                           <th >Employee Name</th>
                           <th style="text-align: center;">Deduction</th>
                           <th style="text-align: center;">Amount Paid</th>
                   </thead>
                   <tbody>
                           @foreach($empList as $emp)
                            <tr class="<?php if(!empty(trim($emp->ar_no))) echo 'success'; ?> " title="<?php if(!empty(trim($emp->ar_no))) echo 'POSTED'; ?> ">
                                <td>
                                  <input type="hidden" name="id[]" value="{{ $emp->id }}">
                                  <input id="id" type="checkbox" name="id{{ $emp->id }}" value="{{ $emp->id }}" <?php if(!empty(trim($emp->ar_no))) echo 'style="display:none"'; ?> onclick="updateTotalAR()">
                                </td>
                                <td>{{ $emp->ctrl_no }}</td>
                                <td>{{ $emp->EmpID }}</td>
                                <td>{{ utf8_encode($emp->FullName) }}</td>
                                <td>
                                  <input type="number" name="deduction{{ $emp->id }}" class="form-control input-sm" value="{{ $emp->deductions }}" disabled>
                                </td>
                                <td>
                                  <input type="number" name="amount{{ $emp->id }}" class="form-control input-sm amount" value="<?php if(!empty(trim($emp->ar_no))) echo $emp->amount; else echo $emp->deductions; ?>" <?php if(!empty(trim($emp->ar_no))) echo 'disabled'; ?> onchange="updateARAmount()">
                                </td>
                            </tr>
                           @endforeach
                           <tr>
                             <td colspan="5" style="text-align: right"><strong>Remaining Balance</strong></td>
                             <td style="text-align: right">Php <span style="font-weight: bold;" id="arBalance"></span></td>
                           </tr>
                   </tbody>
           </table>
    </div>



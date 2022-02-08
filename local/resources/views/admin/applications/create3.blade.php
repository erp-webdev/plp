@extends('admin.layouts.app')
@section('content')

<div class="container-fluid">
    <div class="col-md-6 col-sm-6"> 
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2"><h3>APPLICATION FORM</h3></td>
                </tr>
                <tr>
                    <td><label for=""><input id="type" name="type" type="radio"> NEW</label> </td>
                    <td>
                    <label for=""><input id="type" name="type" type="radio"> RE-AVAILMENT</label>    
                        <br> <span>Previous loan amount: </span>
                        <br> <span>Balance: </span>
                    </td>
                </tr>
                <tr>
                    <td><label for=""><input type="radio" name="special"> REGULAR</label></td>
                    <td><label for=""><input type="radio" name="special"> SPECIAL</label></td>
                </tr>
                <tr>
                    <th>LOCAL / DIRECT LINE</th>
                    <td><input type="text" name="" class="form-control"></td>
                </tr>
                <tr>
                    <th>LOAN AMOUNT</th>
                    <td><input type="text" class="form-control"></td>
                </tr>
                <tr>
                    <th>PURPOSE</th>
                    <td><input type="text" class="form-control"></td>
                </tr>
                <tr>
                    <th>ENDORSED BY</th>
                    <td><input type="text" class="form-control"></td>
                </tr>
                <tr>
                    <th>SURETY/CO-BORROWER</th>
                    <td><input type="text" class="form-control"></td>
                </tr>
                <tr>
                    <td colspan="2" class=""><button type="button" class="btn btn-primary pull-right">Submit Loan Application</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-6 col-sm-6">
        
    </div>
</div>

@endsection
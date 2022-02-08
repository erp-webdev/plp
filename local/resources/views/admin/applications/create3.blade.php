@extends('admin.layouts.app')
@section('content')

<div class="container-fluid">
    <div class="col-md-6 col-sm-6"> 
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2"><h3>TYPE OF APPLICATION</h3></td>
                </tr>
                <tr>
                    <td><input id="type" name="type" type="radio"><label for=""> NEW</label> </td>
                    <td>
                        <input id="type" name="type" type="radio"><label for=""> RE-AVAILMENT</label>    
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

            </tbody>
        </table>
    </div>
</div>

@endsection
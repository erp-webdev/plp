@extends('admin.layouts.app')
@section('content')

<div class="container-fluid">
    <div class="col-md-6 col-sm-6"> 
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2">TYPE OF APPLICATION</td>
                </tr>
                <tr>
                    <td><input id="type" name="type" type="radio"><label for="">NEW</label> </td>
                    <td>
                        <input id="type" name="type" type="radio"><label for="">RE-AVAILMENT</label>    
                        <br> <span>Previous loan amount: </span>
                        <br> <span>Balance: </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
@extends('admin.layouts.app')
@section('content')

<div class="container-fluid">
    <table class="table">
        <tbody>
            <tr>
                <td colspan="2">TYPE OF APPLICATION</td>
            </tr>
            <tr>
                <td><input id="type" type="radio">New</td>
                <td><input id="type" type="radio">Re-availment</td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
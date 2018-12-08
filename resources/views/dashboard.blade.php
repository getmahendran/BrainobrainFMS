@extends('layouts.bobkar')

@section('content')
<h1 class="text-center text-uppercase main_head">dashboard</h1><hr/>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="notification_box_container">
            <div  class="notification_head text-uppercase"><i class="fa fa-bell" aria-hidden="true"></i> Noticeboard</div>
            <div class="notification_body">
                <ul>
                    <a href="#" title="Faculty Training for Level 4 BOB is scheduled on 1/2/2018"><li>Faculty Training for Level 4 BOB scheduled on 1/2/1521 - Register Here</li></a>
                    <a href="#"><li>News Letter 2018</li></a>
                    <a href="#"><li>Notification 3</li></a>
                    <a href="#"><li>Notification 4</li></a>
                    <a href="#"><li>Notification 5</li></a>
                    <a href="#"><li>Notification 6</li></a>
                    <a href="#"><li>Notification 7</li></a>
                </ul>
            </div>
        </div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="widget-container">
            <div class="widget-head text-uppercase">total students</div>
            <div class="widget-content">5474</div>
        </div><br/>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="widget-container">
            <div class="widget-head text-uppercase">total teachers</div>
            <div class="widget-content">5474</div>
        </div><br/>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="widget-container">
            <div class="widget-head text-uppercase">total batches</div>
            <div class="widget-content">5474</div>
        </div>
    </div>
</div>
<div class="row"  style="background-color:#fff;height:550px;padding-top:15px;">
    <div class="col-md-6">
        <h3 class="text-muted">Admission</h3>
        <table class="table">
            <thead class="thead-light">
            <tr>
                <th scope="col">Particular</th>
                <th scope="col">Count</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">New Admission(Current Month)</th>
                <td>15</td>
            </tr>
            <tr>
                <th scope="row">Student Strength</th>
                <td>87</td>
            </tr>
            <tr>
                <th scope="row">Total</th>
                <td>102</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h3 class="text-muted">Fee Collection</h3>
        <table class="table">
            <thead class="thead-light">
            <tr>
                <th scope="col">Particular</th>
                <th scope="col">Current Month</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">Course Fee</th>
                <td>900</td>
            </tr>
            <tr>
                <th scope="row">Exam Fee</th>
                <td>200</td>
            </tr>
            <tr>
                <th scope="row">Total</th>
                <td>1100</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
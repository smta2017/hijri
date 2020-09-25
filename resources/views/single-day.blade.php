@extends('layouts.app')

@section('content')

<div class="col-md-8">
  <div class="tables_months">
    <div class="top_bar">
      <div class="row">
        <div class="col-md-6">
          <img src="./assets/images/calendar.png" alt="">
          <span>@lang('main.home_title')</span>
        </div>
        <div class="col-md-6 thisalignright">
          <span>{{$full_date}}</span>
        </div>
      </div>
    </div>
    <div class="tabless">
      <!-- page 3 -->
      <div class="page3">
        <div class="header">
          <div>
            <img class="img2" src="./assets/images/GrilleCalendare.png" alt="">
          </div>
          <div>
            <h5>TODAY</h5>
            <h3>{{$full_date}}</h3>
          </div>
        </div><!-- end header -->
        <div class="content1">
          <h3>Today | Hijri Calendar </h3>
          <table>
            <tr>
              <td>Full Format </td>
              <td>{{$full_date}}</td>
            </tr>
            <tr>
              <td>Numerical Format </td>
              <td>{{$numeric_date}}</td>
            </tr>
            <tr>
              <td>Today </td>
              <td>{{$the_day}}</td>
            </tr>
          </table>
        </div><!-- end content1 -->
        <div class="content1">
          <h3>Today | Georgian Calendar </h3>
          <table>
            <tr>
              <td>Full Format </td>
              <td>11 September 2020</td>
            </tr>
            <tr>
              <td>Numerical Format </td>
              <td>11 - 9 - 2020</td>
            </tr>
          </table>
        </div><!-- end content2 -->
      </div>
      <!-- end page 3 -->
    </div> <!-- end div tabless -->
  </div> <!-- end div tables_months -->
</div> <!-- end col 8 -->

@endsection

@section('scripts')

<script>
  $("#testbtn").on("click", function(e) {
    e.preventDefault();
    alert("kok");
  });
</script>

@endsection
@extends('layouts.app')

@section('content')

<div class="col-md-8">
    <div class="tables_months">
        <div class="top_bar">
            <div class="row">
                <div class="col-md-6">
                    <img src="./assets/images/calendar.png" alt="">
                    <span>@lang('main.today_date')</span>
                </div>
                <div class="col-md-6 thisalignright">
                    <span>{{$full_date}} @lang('main.correspond_to') {{$full_date_go}}</span>
                </div>
            </div>
        </div>
        
        <div class="tabless">
            <div class="row"> 
                <?php echo $calender; ?>
            </div> <!-- end row -->
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
@extends('layouts.app')

@section('content')

<div class="col-md-8">
    <div class="tables_months">
        <div class="top_bar">
            <div class="row">
                <div class="col-md-6">
                    <img src="./assets/images/calendar.png" alt="">
                    <span>Today's Date</span>
                </div>
                <div class="col-md-6 thisalignright">
                    <span>Friday 9 Muharram 1442 Correspond to 28 August 2020</span>
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
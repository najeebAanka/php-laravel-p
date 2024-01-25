<!DOCTYPE html>
<html lang="en">

@include('dashboard.shared.css')

{{-- enter date modal start --}}
<div class="modal fade" id="addDateModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Enter Date Range</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_date_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="my-2">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_date_btn" class="btn btn-primary">Submit</button>
                    <div id="add_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- enter date modal end --}}



<body>

    <!-- ======= Header ======= -->
    @include('dashboard.shared.top-nav')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('dashboard.shared.side-nav')
    <!-- End Sidebar-->

    <main id="main" class="main">



        <section class="section dashboard">
            <div class="row">

                <div class="container">
                    <!--<div class="row my-5">-->
                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3>Statistics</h3>
                                    <h5 id="dddd">Currently</h5>
                                    <button class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#addDateModal">
                                        <i class="bi-calendar-date me-2"></i>
                                        Date range
                                    </button>
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">Customers</th>
                                                    <th style="text-align: center;">Orders</th>
                                                    <th style="text-align: center;">Sales</th>
                                                </tr>
                                            </thead>
                                            <tbody id="show_all_statistics">

                                                <tr>
                                                    <td colspan="100%" style="text-align: center;">
                                                        <h4 class="mt-3">Loading..</h4>

                                                        <div class="lds-spinner">
                                                            <div></div>
                                                            <div></div>
                                                            <div></div>
                                                            <div></div>
                                                            <div></div>
                                                            <div></div>
                                                            <div></div>
                                                            <div></div>
                                                            <div></div>
                                                        </div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="pages-container"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('dashboard.shared.footer')
    <!-- End Footer -->

    @include('dashboard.shared.js')

    <script>

        $(function() {


            fetchAllStatistics();


            $("#add_date_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_spinner").css("display", "block");
                $("#add_date_btn").text('Adding...');
                $("#add_date_btn").attr('disabled', true);
                $.ajax({
                    url: server + "statistics/request",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        
                        //   var str="<tr><td style='text-align: center;'>"+response.users+"</td><td style='text-align: center;'>"+response.orders+"</td><td style='text-align: center;'>"+response.sales+"</td></tr>";
                        
                          var str="<tr><td><div class='card-body'><div class='d-flex align-items-center'><div class='card-icon rounded-circle d-flex align-items-center justify-content-center'><i class='bi bi-people'></i></div><div class='ps-3'><h6>"+response.users+"</h6></div></div></div></td>"+
                          "<td><div class='card-body'><div class='d-flex align-items-center'><div class='card-icon rounded-circle d-flex align-items-center justify-content-center'><i class='bi bi-cart'></i></div><div class='ps-3'><h6>"+response.orders+"</h6></div></div></div></td>"+
                          "<td><div class='card-body'><div class='d-flex align-items-center'><div class='card-icon rounded-circle d-flex align-items-center justify-content-center'><i class='bi bi-currency-dollar'></i></div><div class='ps-3'><h6>"+response.sales+"</h6></div></div></div></td>"+"</tr>";
                         
                          $("#show_all_statistics").html(str);

                          $("#dddd").html("between " + response.start_date + " and " + response.end_date);
                    
                        $("#add_date_btn").text('Submit');
                        $("#add_date_btn").attr('disabled', false);
                        $("#add_date_form")[0].reset();
                        $("#addDateModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        $('#edit-toast').toast('show');
                    }
                });
            });


            // fetch all statistics ajax request

            function fetchAllStatistics() {
                $.ajax({
                    url: server + "statistics/fetchall",
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r;

                        // var str="<tr><td style='text-align: center;'>"+response.users+"</td><td style='text-align: center;'>"+response.orders+"</td><td style='text-align: center;'>"+response.sales+"</td></tr>";

                        var str="<tr><td><div class='card-body'><div class='d-flex align-items-center'><div class='card-icon rounded-circle d-flex align-items-center justify-content-center'><i class='bi bi-people'></i></div><div class='ps-3'><h6>"+response.users+"</h6></div></div></div></td>"+
                                    "<td><div class='card-body'><div class='d-flex align-items-center'><div class='card-icon rounded-circle d-flex align-items-center justify-content-center'><i class='bi bi-cart'></i></div><div class='ps-3'><h6>"+response.orders+"</h6></div></div></div></td>"+
                                    "<td><div class='card-body'><div class='d-flex align-items-center'><div class='card-icon rounded-circle d-flex align-items-center justify-content-center'><i class='bi bi-currency-dollar'></i></div><div class='ps-3'><h6>"+response.sales+"</h6></div></div></div></td>"+"</tr>";

                            $("#show_all_statistics").html(str);
                        
                    

                    },
                    error: function(response) {
                        console.log("err " + response)

                    }
                });
            }


        });
    </script>



</body>

</html>
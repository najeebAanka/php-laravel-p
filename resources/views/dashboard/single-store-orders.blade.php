<!DOCTYPE html>
<html lang="en">

<?php 
$store  = App\Models\User::find(Route::input('id'));

$s = $store;
?>

@include('dashboard.shared.css')


{{-- edit order modal start --}}
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-order-title" class="modal-title">Edit Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_order_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="order_id" value="-1">
                <div class="modal-body p-4 bg-light">


                    <div class="my-2">
                        <label for="table">Order Details</label>
                        <table class="table table-bordered">
                            <tr>
                               <td style="font-weight: 600">Customer Name</td>
                               <td id="order_customer_name"></td>
                            </tr>
                            <tr>
                               <td style="font-weight: 600">Customer Email</td>
                               <td id="order_customer_email"></td>
                            </tr>
                            <tr>
                               <td style="font-weight: 600">Customer Phone</td>
                               <td id="order_customer_phone"></td>
                            </tr>
                            <tr>
                               <td style="font-weight: 600">Customer Country</td>
                               <td id="order_customer_country"></td>
                            </tr>
                            <tr>
                               <td style="font-weight: 600">Customer City</td>
                               <td id="order_customer_city"></td>
                            </tr>
                            <tr>
                               <td style="font-weight: 600">Customer Street</td>
                               <td id="order_customer_street"></td>
                            </tr>
                            <tr>
                               <td style="font-weight: 600">Customer Building</td>
                               <td id="order_customer_building"></td>
                            </tr>
                            <tr>
                               <td style="font-weight: 600">Customer Floor</td>
                               <td id="order_customer_floor"></td>
                            </tr>
                            <tr>
                               <td style="font-weight: 600">Customer Flat</td>
                               <td id="order_customer_flat"></td>
                            </tr>
                        </table>
                    </div>


                    <div class="my-2">
                        <label for="status">Status</label>
                        <select id="order_status" name="status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In progress</option>
                            <option value="ready">Ready</option>
                        </select>
                    </div>
                    
                </div>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_order_btn" class="btn btn-success">Update Order</button>
                    <div id="edit_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit order modal end --}}


<body>

    <!-- ======= Header ======= -->
    @include('dashboard.shared.store-top-nav')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('dashboard.shared.store-side-nav')
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
                                    <h3><?php echo $s->name; ?> Orders</h3>
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Customer Name</th>
                                                    <th>Customer Email</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                  
                                                    <th style="text-align: center;">Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_orders">

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
        var current_page = 1;
        var maxButtons = 5; // Maximum number of pagination buttons to display

        $(function() {


            $("#edit_order_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_order_btn").text('Updating...');
                $("#edit_order_btn").attr('disabled', true);
                $.ajax({
                    url: server + "store-orders/update"+"/"+<?php echo $s->id; ?>,
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllOrders();
                        }
                        $("#edit_order_btn").text('Update Order');
                        $("#edit_order_btn").attr('disabled', false);
                        $("#edit_order_form")[0].reset();
                        $("#editOrderModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit-toast').toast('show');
                    },
                    error: (res) => {

                        $("#edit_order_btn").text('Update');
                        $("#edit_order_btn").attr('disabled', false);
                    }
                });
            });



            // fetch all orders ajax request

            function fetchAllOrders() {
                $.ajax({
                    url: server + "store-orders/fetchall"+"/"+<?php echo $s->id; ?>+"?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)
                        var str = "";
                        for (var i = 0; i < response.length; i++) {
                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].name +
                                "</td><td>" + response[i].email +
                                // "</td><td>" + response[i].total +
                                "</td><td>" + response[i].grand_total +
                                "</td><td>" + response[i].status +
                                "</td><td style='text-align : center'><button data-id='" + response[i]
                                .id +
                                "'  data-customer_name_en='" + response[i].name +
                                "'  data-customer_email='" + response[i].email +
                                // "'  data-total='" + response[i].total +
                                "'  data-total='" + response[i].grand_total +
                                "'  data-status='" + response[i].status +
                                "'  data-customer_name='" + response[i].name +
                                "'  data-customer_email='" + response[i].email +
                                "'  data-customer_phone='" + response[i].phone +
                                "'  data-customer_country='" + response[i].country +
                                "'  data-customer_city='" + response[i].city +
                                "'  data-customer_street='" + response[i].street +
                                "'  data-customer_building='" + response[i].building +
                                "'  data-customer_floor='" + response[i].floor +
                                "'  data-customer_flat='" + response[i].flat +
                                "'  class='btn btn-success btn-sm mt-1 edit-order-btn'>Edit <i class=\"bi-pencil-square \"></i></button><a data-id='" + response[i]
                                .id +
                                "'  data-customer_name_en='" + response[i].name +
                                "'  data-customer_email='" + response[i].email +
                                // "'  data-total='" + response[i].total +
                                "'  data-total='" + response[i].grand_total +
                                "'  data-status='" + response[i].status +
                                "'  data-customer_name='" + response[i].name +
                                "'  class='btn btn-secondary btn-sm mt-1 view-order-btn' style='margin-left: 4px;' href='" + '{{url("dashboard")}}' +  '/single-store-order-items/' + response[i].store_id + '/' + response[i].id  + "'>View <i class=\"bi-eye \"></i></a><button data-id='" + response[i]
                                .id +
                                "'  data-customer_name='" + response[i].name +
                                "' class='btn btn-sm btn-danger mt-1 del-order-btn' style='margin-left: 4px;'>Remove <i class=\"bi-trash-fill \"></i></button></td></tr>";
                        
                        }

                        if(str.length > 0){
                             $("#show_all_orders").html(str);
                        }
                        else{
                            $("#show_all_orders").html('<tr><td colspan="100%" style="text-align:center">No data found</td></tr>');
                        }


                        //add click listner


                        $('.edit-order-btn').click(function() {


                            let id = $(this).attr('data-id');



                            console.log('cliked : ' + id);
                            $('#order_id').val(id);
                            $('#order_status').val($(this).attr('data-status'));

                            $('#order_customer_name').html($(this).attr('data-customer_name'));
                            $('#order_customer_email').html($(this).attr('data-customer_email'));
                            $('#order_customer_phone').html($(this).attr('data-customer_phone'));
                            $('#order_customer_country').html($(this).attr('data-customer_country'));
                            $('#order_customer_city').html($(this).attr('data-customer_city'));
                            $('#order_customer_street').html($(this).attr('data-customer_street'));
                            $('#order_customer_building').html($(this).attr('data-customer_building'));
                            $('#order_customer_floor').html($(this).attr('data-customer_floor'));
                            $('#order_customer_flat').html($(this).attr('data-customer_flat'));
                           
                            $('#edit-order-title').html("Edit " + $(this).attr('data-customer_name') + " order item");
                            $('#editOrderModal').modal('show');

                        });





                        $('.del-order-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove " + $(this).attr('data-customer_name') +  " order item?")) {
                            // if (confirm("Are you sure to remove order?")) {

                                // call ajax to delete this order
                                $(this).html("Deleting...");
                                $(this).prop("disabled", true);

                                $.ajax({
                                    url: server + "store-orders/delete"+"/"+<?php echo $s->id; ?>,
                                    method: 'delete',
                                    data: {
                                        id: id,
                                        _token: csrf
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        fetchAllOrders();
                                        fetchAllOrdersPagesOnly();
                                        $('#delete-toast').toast('show');
                                    }
                                });

                            }



                        });

                        $('.page-cliker').removeClass('current-page');
                        $('#cliker-' + r.current_page).addClass('current-page');


                    },
                    error: function(response) {
                        console.log("err " + response)

                    }
                });
            }


            function fetchAllOrdersPagesOnly() {
                $.ajax({
                    url: server + "store-orders/fetchall"+"/"+<?php echo $s->id; ?>+"?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)

                        var totalPages = Math.ceil(r.total / 25);
                        var pgstr = "";

                        // Calculate the starting and ending page numbers for the pagination
                        var startPage = Math.max(current_page - Math.floor(maxButtons / 2), 1);
                        var endPage = Math.min(startPage + maxButtons - 1, totalPages);

                        // Generate the previous button
                        if (current_page > 1) {
                            pgstr += "<button class='page-cliker' id='cliker-prev' data-link='" + (
                                current_page - 1) + "'>Previous</button> ";
                        }

                        // Generate the page buttons
                        for (var i = startPage; i <= endPage; i++) {
                            pgstr += "<button class='page-cliker " + (i == current_page ?
                                    "current-page" : "") +
                                "' id='cliker-" + i + "' data-link='" + i + "'>" + i + "</button> ";
                        }

                        // Generate the next button
                        if (current_page < totalPages) {
                            pgstr += "<button class='page-cliker' id='cliker-next' data-link='" + (
                                current_page + 1) + "'>Next</button>";
                        }

                        $('#pages-container').html(pgstr);

                        $('.page-cliker').click(function() {
                            current_page = parseInt($(this).attr('data-link'));
                            console.log("going to page: " + current_page);
                            fetchAllOrdersPagesOnly();
                        });

                        fetchAllOrders();
                    },
                    error: function(response) {
                        console.log("err " + response)
                    }
                });
            }



            console.log("calling .. ")
            fetchAllOrdersPagesOnly();


        });
    </script>

</body>

</html>

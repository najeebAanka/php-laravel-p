<!DOCTYPE html>
<html lang="en">

<?php 
$store  = App\Models\User::find(Route::input('id'));

$s = $store;
?>

@include('dashboard.shared.css')
<style>
    .sm-img {
        max-width: 100px;
    }
    img.blah {
        width: 100%;
        max-width: 300px;
        margin-top: 15px;
        margin-left: auto;
        margin-right: auto;
        background-color: #e1e1e1;
        min-height: 30px;
    }

    img.blah_ {
        width: 100%;
        max-width: 300px;
        margin-top: 15px;
        margin-left: auto;
        margin-right: auto;
        background-color: #e1e1e1;
        min-height: 30px;
    }
</style>

{{-- add new service modal start --}}
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_service_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light">

                    <input type="hidden" name="store_id" id="store_id" value="<?php echo $s->id; ?>">

                    <div class="my-2">
                        <label for="service_id">Service</label>
                        <select name="service_id" class="form-control" required>
                            <option value="" disabled selected style="display:none">Select Service</option>
                            <?php $data = \App\Models\Service::get();
                                 foreach($data as $d){ ?>
                                 <option value="<?php echo $d->id;?>"><?php echo $d->name_en;?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="my-2">
                        <label for="product_id">Product</label>
                        <select id="select_add" name="product_id" class="form-control" onchange="readURL()" required>
                            <option value="" disabled selected style="display:none">Select Product</option>
                            <?php $data = \App\Models\Product::get();
                                 foreach($data as $d){ ?>
                                 <option value="<?php echo $d->id;?>"><?php echo $d->name_en;?></option>
                            <?php }?>
                        </select>
                        <img id="add_image" class="blah" src="" />
                    </div>
                    <div class="my-2">
                        <label for="price">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_service_btn" class="btn btn-primary">Add Service</button>
                    <div id="add_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- add new service modal end --}}


{{-- edit service modal start --}}
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-service-title" class="modal-title">Edit Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_service_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="service_id_" value="-1">
                <div class="modal-body p-4 bg-light">

                    <input type="hidden" name="store_id" id="store_id" value="<?php echo $s->id; ?>">

                    <div class="my-2">
                        <label for="service_id">Service</label>
                        <select id="service_id" name="service_id" class="form-control" required>
                            <?php $data = \App\Models\Service::get();
                                 foreach($data as $d){ ?>
                                 <option value="<?php echo $d->id;?>"><?php echo $d->name_en;?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="my-2">
                        <label for="product_id">Product</label>
                        <select id="product_id" name="product_id" class="form-control" onchange="readURL_()" required>
                            <?php $data = \App\Models\Product::get();
                                 foreach($data as $d){ ?>
                                 <option value="<?php echo $d->id;?>"><?php echo $d->name_en;?></option>
                            <?php }?>
                        </select>
                        <img id="edit_image" class="blah_"  src=""/>
                    </div>
                    <div class="my-2">
                        <label for="price">Price</label>
                        <input id="price" type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
                    </div>

                </div>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_service_btn" class="btn btn-success">Update Service</button>
                    <div id="edit_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit service modal end --}}


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
                                    <h3><?php echo $s->name; ?> Services</h3>
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                                        <i class="bi-plus-circle me-2"></i>
                                        Add New Service
                                    </button>
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Service</th>
                                                    <th>Product</th>
                                                    <th>Image</th>
                                                    <th>Price</th>
                                                  
                                                    <th style="text-align: center;">Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_services">

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

            // add new service ajax request
            $("#add_service_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_spinner").css("display", "block");
                $("#add_service_btn").text('Adding...');
                $("#add_service_btn").attr('disabled', true);
                $.ajax({
                    url: server + "store-services/store"+"/"+<?php echo $s->id; ?>,
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllServices();
                            fetchAllServicesPagesOnly();
                        }
                        $("#add_service_btn").text('Add Service');
                        $("#add_service_btn").attr('disabled', false);
                        $("#add_service_form")[0].reset();
                        $('#add_image').attr('src', '');
                        $("#addServiceModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        $('#add-toast').toast('show');
                    }
                });
            });

            $("#edit_service_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_service_btn").text('Updating...');
                $("#edit_service_btn").attr('disabled', true);
                $.ajax({
                    url: server + "store-services/update"+"/"+<?php echo $s->id; ?>,
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllServices();
                        }
                        $("#edit_service_btn").text('Update Service');
                        $("#edit_service_btn").attr('disabled', false);
                        $("#edit_service_form")[0].reset();
                        $("#editServiceModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit-toast').toast('show');
                    },
                    error: (res) => {

                        $("#edit_service_btn").text('Update');
                        $("#edit_service_btn").attr('disabled', false);
                    }
                });
            });



            // fetch all services ajax request

            function fetchAllServices() {
                $.ajax({
                    // url: server + "stores-services/fetchall?page=" + current_page,
                    url: server + "store-services/fetchall"+"/"+<?php echo $s->id; ?>+"?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)
                        var str = "";
                        for (var i = 0; i < response.length; i++) {
                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].service_name +
                                "</td><td>" + response[i].product_name +
                                "</td><td style='text-align : center'><img class='sm-img' src='" +
                                response[i].product_image +
                                "' /></td><td>" + response[i].price +
                                "</td><td style='text-align : center'><button data-id='" + response[i]
                                .id +
                                "'  data-store_id='" + response[i].store_id +
                                "'  data-store_name='" + response[i].store_name +
                                "'  data-service_id='" + response[i].service_id +
                                "'  data-service_name='" + response[i].service_name +
                                "'  data-product_id='" + response[i].product_id +
                                "'  data-product_name='" + response[i].product_name +
                                "'  data-price='" + response[i].price +
                                "'  data-product_image='" + response[i].product_image +
                                "'  class='btn btn-success btn-sm mt-1 edit-service-btn'>Edit <i class=\"bi-pencil-square \"></i></button><button data-id='" + response[i]
                                .id +
                                "'  data-service_id='" + response[i].service_id +
                                "'  data-service_name='" + response[i].service_name +
                                "'  data-product_name='" + response[i].product_name +
                                "'  data-store_name='" + response[i].store_name +
                                "' class='btn btn-sm btn-danger mt-1 del-service-btn' style='margin-left: 4px;'>Remove <i class=\"bi-trash-fill \"></i></button></td></tr>";
                        }

                        if(str.length > 0){
                             $("#show_all_services").html(str);
                        }
                        else{
                            $("#show_all_services").html('<tr><td colspan="100%" style="text-align:center">No data found</td></tr>');
                        }


                        //add click listner


                        $('.edit-service-btn').click(function() {


                            let id = $(this).attr('data-id');

                            console.log('cliked : ' + id);
                            $('#service_id_').val(id);
                            $('#service_id').val($(this).attr('data-service_id'));
                            $('#product_id').val($(this).attr('data-product_id'));
                            $('#store_id').val($(this).attr('data-store_id'));
                            $('#price').val($(this).attr('data-price'));
                            $('#edit_image').attr('src', $(this).attr('data-product_image'));
                           
                            $('#edit-service-title').html("Edit " + $(this).attr('data-product_name') + " " + $(this).attr('data-service_name') + " service for " + $(this).attr('data-store_name'));
                            $('#editServiceModal').modal('show');

                        });





                        $('.del-service-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove " + $(this).attr('data-product_name') + " " + $(this).attr('data-service_name') + " service for " + $(this).attr('data-store_name') +"?")) {

                                // call ajax to delete this service
                                $(this).html("Deleting...");
                                $(this).prop("disabled", true);

                                $.ajax({
                                    url: server + "store-services/delete"+"/"+<?php echo $s->id; ?>,
                                    method: 'delete',
                                    data: {
                                        id: id,
                                        _token: csrf
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        fetchAllServices();
                                        fetchAllServicesPagesOnly();
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


            function fetchAllServicesPagesOnly() {
                $.ajax({
                    url: server + "store-services/fetchall"+"/"+<?php echo $s->id; ?>+"?page=" + current_page,
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
                            fetchAllServicesPagesOnly();
                        });

                        fetchAllServices();
                    },
                    error: function(response) {
                        console.log("err " + response)
                    }
                });
            }


            console.log("calling .. ")
            fetchAllServicesPagesOnly();


        });
    </script>


<script>
    function readURL() {

        $.ajax({
                    url: server + "store-services/fetchProductImage"+"/"+$('#select_add').val()+"/"+<?php echo $s->id; ?>,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;

                        for (var i = 0; i < response.length; i++) {
                            $('#add_image').attr('src', response[i].product_image);
                        }
             }
        });
    }
</script>
<script>
    function readURL_() {

        $.ajax({
                    url: server + "store-services/fetchProductImage"+"/"+$('#product_id').val()+"/"+<?php echo $s->id; ?>,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;

                        for (var i = 0; i < response.length; i++) {
                            $('#edit_image').attr('src', response[i].product_image);
                        }
             }
        });


    }
</script>



</body>

</html>

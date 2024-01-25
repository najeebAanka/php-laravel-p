<!DOCTYPE html>
<html lang="en">

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

                    <div class="my-2">
                        <label for="name_en">Name (En)</label>
                        <input type="text" name="name_en" class="form-control" placeholder="English Name" required>
                    </div>
                    <div class="my-2">
                        <label for="name_ar">Name (Ar)</label>
                        <input type="text" name="name_ar" class="form-control" placeholder="Arabic Name" required>
                    </div>
                    <div class="my-2">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="form-control" onchange="readURL(this)" required>
                        <img id="add_image" class="blah" src="" />
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
                <input type="hidden" name="id" id="service_id" value="-1">
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="name_en">Name (En)</label>
                        <input type="text" id="service_name_en" name="name_en" class="form-control" placeholder="English Name" required>
                    </div>
                    <div class="my-2">
                        <label for="name_ar">Name (Ar)</label>
                        <input type="text" id="service_name_ar" name="name_ar" class="form-control" placeholder="Arabic Name" required>
                    </div>
                    <div class="my-2">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="form-control" onchange="readURL_(this)" >
                        <img id="edit_image" class="blah_"  src=""/>
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
    @include('dashboard.shared.top-nav')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('dashboard.shared.side-nav')
    <!-- End Sidebar-->

    <main id="main" class="main">

        <!--<div class="pagetitle">-->
        <!--    <h1>Services</h1>-->
        <!--    <nav>-->
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item"><a href="{{ url('dashboard/products') }}">Home</a></li>-->
        <!--            <li class="breadcrumb-item active">Services</li>-->
        <!--        </ol>-->
        <!--    </nav>-->
        <!--</div>--><!-- End Page Title -->
        
        <section class="section dashboard">
            <div class="row">



                <div class="container">
                    <!--<div class="row my-5">-->
                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3>Services</h3>
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
                                                    <th>Name (En)</th>
                                                    <th>Name (Ar)</th>
                                                    <th style="text-align: center;">Image</th>
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
                    url: server + "services/store",
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
                        $("#addServiceModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        $('#add_image').removeAttr('src');
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
                    url: server + "services/update",
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
                        $('#edit_image').removeAttr('src');
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
                    url: server + "services/fetchall?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)
                        var str = "";
                        for (var i = 0; i < response.length; i++) {
                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].name_en +
                                "</td><td>" + response[i].name_ar +
                                "</td><td style='text-align : center'><img class='sm-img' src='" +
                                response[i].image +
                                "' /></td><td style='text-align : center'><button data-id='" + response[i]
                                .id +
                                "'  data-name_en='" + response[i].name_en +
                                "'  data-name_ar='" + response[i].name_ar +
                                "'  data-image='" + response[i].image +
                                "'  class='btn btn-success btn-sm mt-1 edit-service-btn'>Edit <i class=\"bi-pencil-square \"></i></button><button data-id='" + response[i]
                                .id +
                                "'  data-name_en='" + response[i].name_en +
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
                            let img_source = $(this).attr('data-image');


                            console.log('cliked : ' + id);
                            $('#service_id').val(id);
                            $('#service_name_en').val($(this).attr('data-name_en'));
                            $('#service_name_ar').val($(this).attr('data-name_ar'));
                            $('#edit_image').attr("src", img_source );
                            $('#edit-service-title').html("Edit " + $(this).attr('data-name_en'));
                            $('#editServiceModal').modal('show');

                        });





                        $('.del-service-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove " + $(this).attr('data-name_en') + "?")) {

                                // call ajax to delete this service
                                $(this).html("Deleting...");
                                $(this).prop("disabled", true);

                                $.ajax({
                                    url: server + "services/delete",
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
                    url: server + "services/fetchall?page=" + current_page,
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
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.blah').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script>
    function readURL_(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.blah_').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>


</body>

</html>

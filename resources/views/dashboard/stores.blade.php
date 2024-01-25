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
{{-- add new store modal start --}}
<div class="modal fade" id="addStoreModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Store</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_store_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="row">

                        <div class="col-6">
                            <div class="my-2">
                                <label for="name">Name (En)</label>
                                <input type="text" name="name" class="form-control" placeholder="English Name"
                                    required>
                            </div>
                            <div class="my-2">
                                <label for="name_ar">Name (Ar)</label>
                                <input type="text" name="name_ar" class="form-control" placeholder="Arabic Name"
                                    required>
                            </div>
                            <div class="my-2">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="my-2">
                                <label for="phone">Phone</label>
                                <input type="number" name="phone" class="form-control" placeholder="Phone" required>
                            </div>
                            <div class="my-2">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    required>
                            </div>
                            <div class="my-2">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="active">Open</option>
                                    <option value="idle">Closed</option>
                                </select>
                            </div>
                            <div class="my-2">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control" onchange="readURL(this)"
                                    required>
                                <img id="add_image" class="blah" src="" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="my-2">
                                <label for="location">Location</label>
                                <div id="map" style="height: 400px;" class="form-control"></div>
                                <input id="latitude" type="hidden" name="latitude" value="25.2048">
                                <input id="longitude" type="hidden" name="longitude" value="55.2708">
                            </div>

                            <div class="my-2">
                                <label for="country_name">Country</label>
                                <input type="text" name="country_name" class="form-control" placeholder="Country Name"
                                    required>
                            </div>
                            <div class="my-2">
                                <label for="city_name">City</label>
                                <input type="text" name="city_name" class="form-control" placeholder="City Name"
                                    required>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_store_btn" class="btn btn-primary">Add Store</button>
                    <div id="add_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- add new store modal end --}}


{{-- edit store modal start --}}
<div class="modal fade" id="editStoreModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-store-title" class="modal-title">Edit Store</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_store_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="store_id" value="-1">
                <div class="modal-body p-4 bg-light">

                    <div class="row">

                        <div class="col-6">
                            <div class="my-2">
                                <label for="name">Name (En)</label>
                                <input type="text" id="store_name" name="name" class="form-control"
                                    placeholder="English Name" required>
                            </div>
                            <div class="my-2">
                                <label for="name_ar">Name (Ar)</label>
                                <input type="text" id="store_name_ar" name="name_ar" class="form-control"
                                    placeholder="Arabic Name" required>
                            </div>
                            <div class="my-2">
                                <label for="email">Email</label>
                                <input type="email" id="store_email" name="email" class="form-control"
                                    placeholder="Email" required>
                            </div>
                            <div class="my-2">
                                <label for="phone">Phone</label>
                                <input type="number" id="store_phone" name="phone" class="form-control"
                                    placeholder="Phone" required>
                            </div>
                            <div class="my-2">
                                <label for="password">Password</label>
                                <input type="password" id="store_password" name="password" class="form-control"
                                    placeholder="Password" required>
                            </div>
                            <div class="my-2">
                                <label for="status">Status</label>
                                <select id="store_status" name="status" class="form-control" required>
                                    <option value="active">Open</option>
                                    <option value="idle">Closed</option>
                                </select>
                            </div>
                            <div class="my-2">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control" onchange="readURL_(this)">
                                <img id="edit_image" class="blah_" src="" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="my-2">
                                <label for="location">Location</label>
                                <div id="map_" style="height: 400px;" class="form-control"></div>
                                <input id="latitude_" type="hidden" name="latitude">
                                <input id="longitude_" type="hidden" name="longitude">
                            </div>

                            <div class="my-2">
                                <label for="country_name">Country</label>
                                <input type="text" id="store_country_name" name="country_name" class="form-control"
                                    placeholder="Country Name" required>
                            </div>
                            <div class="my-2">
                                <label for="city_name">City</label>
                                <input type="text" id="store_city_name" name="city_name" class="form-control"
                                    placeholder="City Name" required>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_store_btn" class="btn btn-success">Update Store</button>
                    <div id="edit_spinner" class="spinner-border text-primary" role="status"
                        style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit store modal end --}}


<body>

    <!-- ======= Header ======= -->
    @include('dashboard.shared.top-nav')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('dashboard.shared.side-nav')
    <!-- End Sidebar-->

    <main id="main" class="main">

        <!--<div class="pagetitle">-->
        <!--    <h1>Stores</h1>-->
        <!--    <nav>-->
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item"><a href="{{ url('dashboard/products') }}">Home</a></li>-->
        <!--            <li class="breadcrumb-item active">Stores</li>-->
        <!--        </ol>-->
        <!--    </nav>-->
        <!--</div>-->
        <!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">



                <div class="container">
                    <!--<div class="row my-5">-->
                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3>Stores</h3>
                                    <button class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#addStoreModal">
                                        <i class="bi-plus-circle me-2"></i>
                                        Add New Store
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
                                                    <th>Email</th>
                                                    <th>Image</th>

                                                    <th style="text-align: center;">Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_stores">

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



    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKRRsr96xqR_Hy9dYcAYVFmW2O5zBPjaw"></script>

    <script>
        $('#addStoreModal').on('shown.bs.modal', function(e) {


            var map;
            var marker;

            // Set the initial location to your desired coordinates
            var initialLocation = {
                lat: 25.2048,
                lng: 55.2708
            };

            // Create the map
            map = new google.maps.Map(document.getElementById('map'), {
                center: initialLocation,
                zoom: 15
            });

            // Create the marker
            marker = new google.maps.Marker({
                position: initialLocation,
                map: map,
                draggable: true
            });

            // Add an event listener to update the marker's position when the user clicks on the map
            google.maps.event.addListener(map, 'click', function(event) {
                marker.setPosition(event.latLng);
            });

            // Add an event listener to update the form fields when the marker is dragged
            google.maps.event.addListener(marker, 'dragend', function(event) {
                $('#latitude').val(marker.getPosition().lat());
                $('#longitude').val(marker.getPosition().lng());
            });


        })
    </script>

    <script>
        var current_page = 1;
        var maxButtons = 5; // Maximum number of pagination buttons to display

        $(function() {

            // add new store ajax request
            $("#add_store_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_spinner").css("display", "block");
                $("#add_store_btn").text('Adding...');
                $("#add_store_btn").attr('disabled', true);
                $.ajax({
                    url: server + "stores/store",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllStores();
                            fetchAllStoresPagesOnly();
                        }
                        $("#add_store_btn").text('Add Store');
                        $("#add_store_btn").attr('disabled', false);
                        $("#add_store_form")[0].reset();
                        $("#addStoreModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        $('#add_image').removeAttr('src');
                        $('#add-toast').toast('show');
                    },
                    error: (res) => {

                        $("#add_store_btn").text('Add Store');
                        $("#add_store_btn").attr('disabled', false);
                        $("#add_store_form")[0].reset();
                        $("#addStoreModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        $('#validation-error-toast').toast('show');
                    }

                    //--------------------------------retrieve error in toast
                    // error: function(xhr, status, error) {

                    //     $("#add_store_btn").text('Add Store');
                    //     $("#add_store_btn").attr('disabled', false);
                    //     $("#add_store_form")[0].reset();
                    //     $("#addStoreModal .btn-close").click();
                    //     $("#add_spinner").css("display", "none");

                    //     // Error case - validation errors
                    //     if (xhr.status === 400) {
                    //         var errors = xhr.responseJSON.errors;

                    //         // Display the validation errors in a toast notification
                    //         for (var field in errors) {
                    //             if (errors.hasOwnProperty(field)) {
                    //                 var errorMessages = errors[field].join('<br>');
                    //                 // Toastify({
                    //                 //     text: errorMessages,
                    //                 //     duration: 5000,
                    //                 //     gravity: 'bottom',
                    //                 //     position: 'left',
                    //                 // }).showToast();

                    //                 $('#validation-error-toast').html(errorMessages);
                    //                 $('#validation-error-toast').toast('show');
                    //             }
                    //         }
                    //     } else {
                    //         // Handle other error cases here
                    //     }
                    // }
                    //------------------------------------------------------------


                });
            });

            $("#edit_store_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_store_btn").text('Updating...');
                $("#edit_store_btn").attr('disabled', true);
                $.ajax({
                    url: server + "stores/update",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllStores();
                        }
                        $("#edit_store_btn").text('Update Store');
                        $("#edit_store_btn").attr('disabled', false);
                        $("#edit_store_form")[0].reset();
                        $("#editStoreModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit_image').removeAttr('src');
                        $('#edit-toast').toast('show');
                    },
                    error: (res) => {

                        $("#edit_store_btn").text('Update Store');
                        $("#edit_store_btn").attr('disabled', false);
                        $("#edit_store_form")[0].reset();
                        $("#editStoreModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#validation-error-toast').toast('show');
                    }
                });
            });



            // fetch all stores ajax request

            function fetchAllStores() {
                $.ajax({
                    url: server + "stores/fetchall?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)
                        var str = "";
                        for (var i = 0; i < response.length; i++) {
                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].name +
                                "</td><td>" + response[i].name_ar +
                                "</td><td>" + response[i].email +
                                "</td><td style='text-align : center'><img class='sm-img' src='" +
                                response[i].image +
                                "' /></td><td style='text-align : center'><button data-id='" + response[
                                    i]
                                .id +
                                "'  data-name='" + response[i].name +
                                "'  data-name_ar='" + response[i].name_ar +
                                "'  data-email='" + response[i].email +
                                "'  data-phone='" + response[i].phone +
                                "'  data-password='" + response[i].password +
                                "'  data-status='" + response[i].status +
                                "'  data-latitude='" + response[i].latitude +
                                "'  data-longitude='" + response[i].longitude +
                                "'  data-image='" + response[i].image +
                                "'  data-country_name='" + response[i].country_name +
                                "'  data-city_name='" + response[i].city_name +
                                "'  class='btn btn-success btn-sm mt-1 edit-store-btn'>Edit <i class=\"bi-pencil-square \"></i></button><button data-id='" +
                                response[i]
                                .id +
                                "'  data-name='" + response[i].name +
                                "' class='btn btn-sm btn-danger mt-1 del-store-btn' style='margin-left: 4px;'>Remove <i class=\"bi-trash-fill \"></i></button></td></tr>";
                        }


                        if (str.length > 0) {
                            $("#show_all_stores").html(str);
                        } else {
                            $("#show_all_stores").html(
                                '<tr><td colspan="100%" style="text-align:center">No data found</td></tr>'
                            );
                        }

                        //add click listner


                        $('.edit-store-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let img_source = $(this).attr('data-image');



                            console.log('cliked : ' + id);
                            $('#store_id').val(id);
                            $('#store_name').val($(this).attr('data-name'));
                            $('#store_name_ar').val($(this).attr('data-name_ar'));
                            $('#store_country_name').val($(this).attr('data-country_name'));
                            $('#store_city_name').val($(this).attr('data-city_name'));
                            $('#store_email').val($(this).attr('data-email'));
                            $('#store_phone').val($(this).attr('data-phone'));
                            $('#store_password').val($(this).attr('data-password'));
                            $('#store_status').val($(this).attr('data-status'));
                            $('#edit_image').attr("src", img_source);


                            $('#latitude_').val($(this).attr('data-latitude'));
                            $('#longitude_').val($(this).attr('data-longitude'));

                            var map;
                            var marker;
                            var d = parseFloat($(this).attr('data-latitude'));
                            var f = parseFloat($(this).attr('data-longitude'));

                            // Set the initial location to your desired coordinates
                            var initialLocation = {
                                lat: d,
                                lng: f
                            };

                            // Create the map
                            map = new google.maps.Map(document.getElementById('map_'), {
                                center: initialLocation,
                                zoom: 15
                            });

                            // Create the marker
                            marker = new google.maps.Marker({
                                position: initialLocation,
                                map: map,
                                draggable: true
                            });

                            // Add an event listener to update the marker's position when the user clicks on the map
                            google.maps.event.addListener(map, 'click', function(event) {
                                marker.setPosition(event.latLng);
                            });

                            // Add an event listener to update the form fields when the marker is dragged
                            google.maps.event.addListener(marker, 'dragend', function(event) {
                                $('#latitude_').val(marker.getPosition().lat());
                                $('#longitude_').val(marker.getPosition().lng());
                            });



                            $('#edit-store-title').html("Edit " + $(this).attr('data-name'));
                            $('#editStoreModal').modal('show');

                        });





                        $('.del-store-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove " + $(this).attr('data-name') +
                                    "?")) {

                                // call ajax to delete this store
                                $(this).html("Deleting...");
                                $(this).prop("disabled", true);

                                $.ajax({
                                    url: server + "stores/delete",
                                    method: 'delete',
                                    data: {
                                        id: id,
                                        _token: csrf
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        fetchAllStores();
                                        fetchAllStoresPagesOnly();
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




            function fetchAllStoresPagesOnly() {
                $.ajax({
                    url: server + "stores/fetchall?page=" + current_page,
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
                            fetchAllStoresPagesOnly();
                        });

                        fetchAllStores();
                    },
                    error: function(response) {
                        console.log("err " + response)
                    }
                });
            }


            console.log("calling .. ")
            fetchAllStoresPagesOnly();



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

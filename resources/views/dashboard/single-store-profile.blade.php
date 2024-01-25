<!DOCTYPE html>
<html lang="en">

<?php
$store = App\Models\User::find(Route::input('id'));

$s = $store;
?>

@include('dashboard.shared.css')

<style>
    .sm-img {
        max-width: 300px;
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


{{-- edit store modal start --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-store-title" class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_store_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="store_id_" value="-1">
                <div class="modal-body p-4 bg-light">

                    {{-- <input type="hidden" name="store_id" id="store_id" value="<?php echo $s->id; ?>"> --}}

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
                            {{-- <div class="my-2">
                                <label for="password">Password</label>
                                <input type="password" id="store_password" name="password" class="form-control"
                                    placeholder="Password" required>
                            </div> --}}
                            <div class="my-2">
                                <label for="phone">Phone</label>
                                <input type="number" id="store_phone" name="phone" class="form-control"
                                    placeholder="Phone" required>
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
                    <div id="edit_spinner" class="spinner-border text-primary" role="status" style="display: none;">
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
                                    <h3>Profile</h3>
                                    <span id="profile_button">
                                        {{-- <button class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#editProfileModal">
                                        <i class="bi-pencil me-2"></i>
                                        Update Profile
                                    </button> --}}
                                    </span>
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    {{-- <th>Title</th>
                                                    <th>Content</th> --}}

                                                    {{-- <th style="text-align: center;">Actions</th> --}}

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_entries">

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
        var current_page = 1;

        $(function() {



            $("#edit_store_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_store_btn").text('Updating...');
                $("#edit_store_btn").attr('disabled', true);
                $.ajax({
                    url: server + "store-profile/update" + "/" + <?php echo $s->id; ?>,
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllEntries();
                        }
                        $("#edit_store_btn").text('Update Profile');
                        $("#edit_store_btn").attr('disabled', false);
                        $("#edit_store_form")[0].reset();
                        $("#editProfileModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit-toast').toast('show');
                    },
                    error: (res) => {

                        $("#edit_store_btn").text('Update Store');
                        $("#edit_store_btn").attr('disabled', false);
                        $("#edit_store_form")[0].reset();
                        $("#editProfileModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#validation-error-toast').toast('show');

                    }
                });
            });




            function fetchAllEntries() {
                $.ajax({
                    url: server + "store-profile/fetchall" + "/" + <?php echo $s->id; ?> + "?page=" +
                        current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(response) {
                        var str = "";
                        var strr = "";
                        str = "<tr><td style='text-align : center'>" + "<b>Name (En)</b>" +
                            "</td><td style='text-align : center'>" + response.name +
                            "<tr><td style='text-align : center'>" + "<b>Name (Ar)</b>" +
                            "</td><td style='text-align : center'>" + response
                            .name_ar + "</td></tr>" + "<tr><td style='text-align : center'>" +
                            "<b>Email</b>" + "</td><td style='text-align : center'>" + response
                            .email + "</td></tr>" + "<tr><td style='text-align : center'>" +
                            "<b>Phone</b>" + "</td><td style='text-align : center'>" + response
                            .phone + "</td></tr>" + "<tr><td style='text-align : center'>" +
                            "<b>Image</b>" +
                            "</td><td style='text-align : center'><img class='sm-img' src='" +
                            response.image +
                            "' /></td></tr>";



                        $("#show_all_entries").html(str);

                        strr = "<button data-id='" + response
                            .id +
                            "'  data-name='" + response.name +
                            "'  data-name_ar='" + response.name_ar +
                            "'  data-email='" + response.email +
                            "'  data-phone='" + response.phone +
                            "'  data-status='" + response.status +
                            "'  data-latitude='" + response.latitude +
                            "'  data-longitude='" + response.longitude +
                            "'  data-country_name='" + response.country_name +
                            "'  data-city_name='" + response.city_name +
                            "'  data-image='" + response.image +
                            "'  class='btn btn-light edit-store-btn' data-bs-toggle='modal' data-bs-target='#editProfileModal'><i class=\"bi-pencil-square \">Update Profile</i></button>";

                        $("#profile_button").html(strr);



                        //add click listner


                        $('.edit-store-btn').click(function() {


                            let id = $(this).attr('data-id');

                            console.log('cliked : ' + id);
                            $('#store_id_').val(id);
                            // $('#store_id').val($(this).attr('data-store_id'));
                            $('#store_name').val($(this).attr('data-name'));
                            $('#store_name_ar').val($(this).attr('data-name_ar'));
                            $('#store_email').val($(this).attr('data-email'));
                            $('#store_phone').val($(this).attr('data-phone'));
                            $('#store_status').val($(this).attr('data-status'));
                            $('#store_country_name').val($(this).attr('data-country_name'));
                            $('#store_city_name').val($(this).attr('data-city_name'));
                            $('#edit_image').attr('src', $(this).attr('data-image'));


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


                            $('#editProfileModal').modal('show');

                        });




                    },
                    error: function(response) {
                        console.log("err " + response)

                    }
                });
            }

            fetchAllEntries();


        });
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

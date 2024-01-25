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
{{-- add new home banner modal start --}}
<div class="modal fade" id="addHomeBannerModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Home Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_home_banner_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="direction_type">Direction Type</label>
                        <select id="direction_type_add" name="direction_type" class="form-control" required>
                            <option value="" disabled selected style="display:none">Select Direction Type</option>
                            <option value="website">Website</option>
                            <option value="store">Store</option>
                        </select>
                    </div>
                    <div id="website_select_id" class="my-2" style="display: none;">
                        <label for="direction_id">Direction</label>
                        <input id="website_select_id_value" type="text" class="form-control" placeholder="https://">
                    </div>
                    <div id="store_select_id" class="my-2" style="display: none;">
                        <label for="direction_id">Direction</label>
                        <select id="store_select_id_value" class="form-control">
                            <option value="" disabled selected style="display:none">Select Store</option>
                            <?php
                            $data = \App\Models\User::where('user_type', 'store')->get();
                                foreach($data as $d) {?>
                                 <option value="<?php echo $d->id; ?>"><?php echo $d->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="my-2">
                        <label for="expiration_date">Expiration Date</label>
                        <input type="date" name="expiration_date" class="form-control" placeholder="Expiration Date" required>
                    </div>
                   
                    <div class="my-2">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="form-control" onchange="readURL(this)" required>
                        <img id="add_image" class="blah" src="" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_home_banner_btn" class="btn btn-primary">Add Home Banner</button>
                    <div id="add_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- add new home banner modal end --}}


{{-- edit home banner modal start --}}
<div class="modal fade" id="editHomeBannerModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-home-banner-title" class="modal-title">Edit Home Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_home_banner_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="home_banner_id" value="-1">
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="direction_type">Direction Type</label>
                        <select id="home_banner_direction_type" name="direction_type" class="form-control" required>
                            {{-- <option value="" disabled selected style="display:none">Select Direction Type</option> --}}
                            <option value="website">Website</option>
                            <option value="store">Store</option>
                        </select>
                    </div>
                    <div id="website_select_id_edit" class="my-2" style="display: none;">
                        <label for="direction_id">Direction</label>
                        <input id="website_select_id_value_edit" type="text" class="form-control" placeholder="https://">
                    </div>
                    <div id="store_select_id_edit" class="my-2" style="display: none;">
                        <label for="direction_id">Direction</label>
                        <select id="store_select_id_value_edit" class="form-control">
                            <option value="" disabled selected style="display:none">Select Store</option>
                            <?php
                            $data = \App\Models\User::where('user_type', 'store')->get();
                                foreach($data as $d) {?>
                                 <option value="<?php echo $d->id; ?>"><?php echo $d->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="my-2">
                        <label for="expiration_date">Expiration Date</label>
                        <input id="home_banner_expiration_date" type="date" name="expiration_date" class="form-control" placeholder="Expiration Date" required>
                    </div>
                  
                    <div class="my-2">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="form-control" onchange="readURL_(this)" >
                        <img id="edit_image" class="blah_"  src=""/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_home_banner_btn" class="btn btn-success">Update
                        Home Banner</button>
                    <div id="edit_spinner" class="spinner-border text-primary" role="status"
                        style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit home banner modal end --}}



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
                                    <h3>Home Banners</h3>
                                    <button class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#addHomeBannerModal">
                                        <i class="bi-plus-circle me-2"></i>
                                        Add New Home Banner
                                    </button>
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Direction Type</th>
                                                    <th>Expiration Date</th>
                                                    <th style="text-align: center;">Image</th>
                                                    <th style="text-align: center;">Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_home_banners">

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

            // add new home banner ajax request
            $("#add_home_banner_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_spinner").css("display", "block");
                $("#add_home_banner_btn").text('Adding...');
                $("#add_home_banner_btn").attr('disabled', true);
                $.ajax({
                    url: server + "home-banners/store",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllHomeBanners();
                            fetchAllHomeBannersPagesOnly();
                        }
                        $("#add_home_banner_btn").text('Add Home Banner');
                        $("#add_home_banner_btn").attr('disabled', false);
                        $("#add_home_banner_form")[0].reset();
                        $("#addHomeBannerModal .btn-close").click();
                        $("#add_spinner").css("display", "none");
                        //--------------------------------------------
                        $("#website_select_id").css("display", "none");
                        $("#store_select_id").css("display", "none");
                        $('#website_select_id_value').removeAttr('name');
                        $('#store_select_id_value').removeAttr('name');
                        $('#website_select_id_value').prop('required',false);
                        $('#store_select_id_value').prop('required',false);
                        //---------------------------------------------
                        $('#add_image').removeAttr('src');
                        $('#add-toast').toast('show');
                    }
                });
            });

            $("#edit_home_banner_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_home_banner_btn").text('Updating...');
                $("#edit_home_banner_btn").attr('disabled', true);
                $.ajax({
                    url: server + "home-banners/update",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllHomeBanners();
                        }
                        $("#edit_home_banner_btn").text('Update Home Banner');
                        $("#edit_home_banner_btn").attr('disabled', false);
                        $("#edit_home_banner_form")[0].reset();
                        $("#editHomeBannerModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit_image').removeAttr('src');
                        $('#edit-toast').toast('show');
                    },
                    // error: (res) => {

                    //     $("#edit_home_banner_btn").text('Update');
                    //     $("#edit_home_banner_btn").attr('disabled', false);
                    // }
                });
            });



            // fetch all home banners ajax request

            function fetchAllHomeBanners() {
                $.ajax({
                    url: server + "home-banners/fetchall?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)
                        var str = "";
                        for (var i = 0; i < response.length; i++) {
                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].direction_type +
                                "</td><td>" + response[i].exp_date +
                                "</td><td style='text-align : center'><img class='sm-img' src='" +
                                response[i].image +
                                "' /></td><td style='text-align : center'><button data-id='" + response[
                                    i]
                                .id +
                                "'  data-direction_type='" + response[i].direction_type +
                                "'  data-direction_id='" + response[i].direction_id +
                                "'  data-exp_date='" + response[i].exp_date +
                                "'  data-image='" + response[i].image +
                                "'  class='btn btn-success btn-sm mt-1 edit-home-banner-btn' style='margin-left: 4px;'>Edit <i class=\"bi-pencil-square \"></i></button><button data-id='" +
                                response[i]
                                .id +
                                "'  data-direction_type='" + response[i].direction_type +
                                "' class='btn btn-sm btn-danger mt-1 del-home-banner-btn' style='margin-left: 4px;'>Remove <i class=\"bi-trash-fill \"></i></button></td></tr>";
                        }

                        if(str.length > 0){
                            $("#show_all_home_banners").html(str);
                        }
                        else{
                            $("#show_all_home_banners").html('<tr><td colspan="100%" style="text-align:center">No data found</td></tr>');
                        }

                        //add click listner

                        $('.edit-home-banner-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let img_source = $(this).attr('data-image');

                            console.log('cliked : ' + id);

                            if($(this).attr('data-direction_type') == "store"){
                                $('#store_select_id_edit').css("display", "block" );
                                $('#website_select_id_edit').css("display", "none" );
                                $('#store_select_id_value_edit').val($(this).attr('data-direction_id'));
                                $('#store_select_id_value_edit').attr('name', 'direction_id');
                                $('#website_select_id_value_edit').attr('name', '');
                                $('#website_select_id_value_edit').attr('required', false);
                            }else if($(this).attr('data-direction_type') == "website"){
                                $('#store_select_id_edit').css("display", "none" );
                                $('#website_select_id_edit').css("display", "block" );
                                $('#website_select_id_value_edit').val($(this).attr('data-direction_id'));
                                $('#website_select_id_value_edit').attr('name', 'direction_id');
                                $('#store_select_id_value_edit').attr('name', '');
                                $('#store_select_id_value_edit').attr('required', false);
                            }


                            $('#home_banner_id').val(id);
                            $('#home_banner_direction_type').val($(this).attr('data-direction_type'));
                            $('#home_banner_expiration_date').val($(this).attr('data-exp_date'));
                            $('#edit_image').attr("src", img_source );
                            $('#edit-home-banner-title').html("Edit " + $(this).attr('data-direction_type'));
                            $('#editHomeBannerModal').modal('show');

                        });


                        $('.del-home-banner-btn').click(function() {


                            let id = $(this).attr('data-id');
                            let csrf = '{{ csrf_token() }}';


                            console.log('cliked : ' + id);

                            if (confirm("Are you sure to remove this " + $(this).attr('data-direction_type') +
                                    " banner?")) {

                                // call ajax to delete this home banner
                                $(this).html("Deleting...");
                                $(this).prop("disabled", true);

                                $.ajax({
                                    url: server + "home-banners/delete",
                                    method: 'delete',
                                    data: {
                                        id: id,
                                        _token: csrf
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        fetchAllHomeBanners();
                                        fetchAllHomeBannersPagesOnly();
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

            function fetchAllHomeBannersPagesOnly() {
                $.ajax({
                    url: server + "home-banners/fetchall?page=" + current_page,
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
                            fetchAllHomeBannersPagesOnly();
                        });

                        fetchAllHomeBanners();
                    },
                    error: function(response) {
                        console.log("err " + response)
                    }
                });
            }


            console.log("calling .. ")
            fetchAllHomeBannersPagesOnly();


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

<script>

    var el = document.getElementById("direction_type_add");
    el.addEventListener("change", function() {
      
    
        document.querySelector('#website_select_id').style.display = 'none';
        document.querySelector('#store_select_id').style.display = 'none';
        
        document.getElementById("website_select_id_value").required = false;
        document.getElementById("store_select_id_value").required = false;

        document.getElementById("website_select_id_value").setAttribute("name","");
        document.getElementById("store_select_id_value").setAttribute("name","");
        
    
      if (this.value == "website") {
        document.querySelector('#website_select_id').style.display = 'block';
    
        document.getElementById("website_select_id_value").required = true;

        document.getElementById("website_select_id_value").setAttribute("name","direction_id");
    
     } else if (this.value == "store") {
        document.querySelector('#store_select_id').style.display = 'block';
    
        document.getElementById("store_select_id_value").required = true;

        document.getElementById("store_select_id_value").setAttribute("name","direction_id");

     }
    }, false);
    
    </script>


<script>

    var el = document.getElementById("home_banner_direction_type");
    el.addEventListener("change", function() {
      
    
        document.querySelector('#website_select_id_edit').style.display = 'none';
        document.querySelector('#store_select_id_edit').style.display = 'none';
        
        document.getElementById("website_select_id_value_edit").required = false;
        document.getElementById("store_select_id_value_edit").required = false;

        document.getElementById("website_select_id_value_edit").setAttribute("name","");
        document.getElementById("store_select_id_value_edit").setAttribute("name","");
        
    
      if (this.value == "website") {
        document.querySelector('#website_select_id_edit').style.display = 'block';
    
        document.getElementById("website_select_id_value_edit").required = true;

        document.getElementById("website_select_id_value_edit").setAttribute("name","direction_id");
    
     } else if (this.value == "store") {
        document.querySelector('#store_select_id_edit').style.display = 'block';
    
        document.getElementById("store_select_id_value_edit").required = true;

        document.getElementById("store_select_id_value_edit").setAttribute("name","direction_id");

     }
    }, false);
    
    </script>


</body>

</html>
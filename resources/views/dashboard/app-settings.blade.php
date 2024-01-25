<!DOCTYPE html>
<html lang="en">

@include('dashboard.shared.css')


{{-- edit app setting modal start --}}
<div class="modal fade" id="editAppSettingModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-app-setting-title" class="modal-title">Edit App Setting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_app_setting_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="app_setting_id" value="-1">
                <div class="modal-body p-4 bg-light">

                    <div class="my-2">
                        <label for="value_en">Content (En)</label>
                        <textarea id="app_setting_value_en" name="value_en" class="form-control" placeholder="English Content" rows="5" required></textarea>
                    </div>
                    <div class="my-2">
                        <label for="value_ar">Content (Ar)</label>
                        <textarea id="app_setting_value_ar" name="value_ar" class="form-control" placeholder="Arabic Content" rows="5" required></textarea>
                    </div>
        
                </div>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_app_setting_btn" class="btn btn-success">Update App Setting</button>
                    <div id="edit_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit app setting modal end --}}


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
                                    <h3>App Settings</h3>
                                    
                                </div>
                                <div class="card-body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Title</th>
                                                    <th>Content (En)</th>
                                                    <th>Content (Ar)</th>
                                                    <th style="text-align: center;">Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody id="show_all_app_settings">

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

            $("#edit_app_setting_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#edit_spinner").css("display", "block");
                $("#edit_app_setting_btn").text('Updating...');
                $("#edit_app_setting_btn").attr('disabled', true);
                $.ajax({
                    url: server + "app-settings/update",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            fetchAllAppSettings();
                        }
                        $("#edit_app_setting_btn").text('Update App Setting');
                        $("#edit_app_setting_btn").attr('disabled', false);
                        $("#edit_app_setting_form")[0].reset();
                        $("#editAppSettingModal").modal('hide');
                        $("#edit_spinner").css("display", "none");
                        $('#edit-toast').toast('show');
                    },
                    error: (res) => {

                
                    }
                });
            });



            // fetch all app setting ajax request

            function fetchAllAppSettings() {
                $.ajax({
                    url: server + "app-settings/fetchall?page=" + current_page,
                    dataType: 'json',
                    method: 'get',
                    success: function(r) {
                        let response = r.data;
                        console.log(response.length)
                        var str = "";
                        for (var i = 0; i < response.length; i++) {
                            str += "<tr><td>" + response[i].id + "</td><td>" + response[i].code +
                                "</td><td>" + response[i].value_en +
                                "</td><td>" + response[i].value_ar +
                                "</td><td style='text-align : center'><button data-id='" + response[i]
                                .id +
                                "'  data-code='" + response[i].code +
                                "'  data-value_en='" + response[i].value_en +
                                "'  data-value_ar='" + response[i].value_ar +
                                "'  class='btn btn-success btn-sm mt-1 edit-app-setting-btn'>Edit <i class=\"bi-pencil-square \"></i></button></td></tr>";
                        }

                        if(str.length > 0){
                             $("#show_all_app_settings").html(str);
                        }
                        else{
                            $("#show_all_app_settings").html('<tr><td colspan="100%" style="text-align:center">No data found</td></tr>');
                        }


                        //add click listner


                        $('.edit-app-setting-btn').click(function() {


                            let id = $(this).attr('data-id');


                            console.log('cliked : ' + id);
                            $('#app_setting_id').val(id);
                            $('#app_setting_value_en').val($(this).attr('data-value_en'));
                            $('#app_setting_value_ar').val($(this).attr('data-value_ar'));
                            $('#edit-app-setting-title').html("Edit " + $(this).attr('data-code'));
                            $('#editAppSettingModal').modal('show');

                        });





                        $('.page-cliker').removeClass('current-page');
                        $('#cliker-' + r.current_page).addClass('current-page');


                    },
                    error: function(response) {
                        console.log("err " + response)

                    }
                });
            }

            function fetchAllAppSettingsPagesOnly() {
                $.ajax({
                    url: server + "app-settings/fetchall?page=" + current_page,
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
                            fetchAllAppSettingsPagesOnly();
                        });

                        fetchAllAppSettings();
                    },
                    error: function(response) {
                        console.log("err " + response)
                    }
                });
            }

            console.log("calling .. ")
            fetchAllAppSettingsPagesOnly();



        });
    </script>


</body>

</html>

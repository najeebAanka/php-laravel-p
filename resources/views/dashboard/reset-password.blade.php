
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Dashboard | Reset Password </title>
<link rel="icon" type="image/x-icon" href="{{url('assets/img/apple-touch-icon.png')}}" />
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
<link rel="stylesheet"  href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />



        <link rel="icon" type="image/x-icon" href="{{asset("assets/img/apple-touch-icon")}}" />
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
          href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
          rel="stylesheet"
        />
    
        <!-- Icons. Uncomment required icon fonts -->
        <link rel="stylesheet" href="{{asset("assets/vendor/fonts/boxicons.css")}}" />
    
        <!-- Core CSS -->
        <link rel="stylesheet" href="{{asset("assets/vendor/css/core.css")}}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{asset("assets/vendor/css/theme-default.css")}}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{asset("assets/css/demo.css")}}" />
    
        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{asset("assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css")}}" />
    
        <link rel="stylesheet" href="{{asset("assets/vendor/libs/apex-charts/apex-charts.css")}}" />
    
        <!-- Page CSS -->
    
        <!-- Helpers -->
        <script src="{{asset("assets/vendor/js/helpers.js")}}"></script>
    
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="{{asset("assets/js/config.js")}}"></script>


@include('dashboard.shared.css')
<style>

.login,
.image {
  min-height: 100vh;
}

.bg-image {
  background-image: url('{{url('assets/img/blue-tie-dye-border-plasticine-clay-textured-aesthetic-background-diy-creative-art_53876-133055.avif')}}');
  background-size: cover;
  background-position: center center;
}


    </style>


</head>




<body>

    <div class="container-fluid" style="padding-left: 1rem; padding-right: 1rem">
        <div class="row no-gutter">
            <!-- The image half -->
            <div class="col-md-6 d-none d-md-flex bg-image"></div>
    
    
            <!-- The content half -->
            <div class="col-md-6 bg-light">
                <div class="login d-flex align-items-center py-5">
    
                    <!-- Demo content-->
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-10 col-xl-7 mx-auto">
                              <div style="text-align: center">
                            <img src="{{url('assets/img/apple-touch-icon.png')}}" width="200" height="130" />
                              </div>
                            <hr>
                                {{-- <h3 class="display-4" style="color: #a16825">Sabayek</h3> --}}
                                <h5 class="text-muted mb-4">Reset Password</h5>


                                <form id="formAuthentication" class="mb-3" action="{{ url('dashboard/backend/reset-password') }}" method="POST">
                                    @csrf
                                    

                                    <div class="form-password-toggle">
                                        <label class="form-label" for="current-password">Current Password</label>
                                        <div class="input-group">
                                          <input
                                            type="password"
                                            name="current" 
                                            class="form-control"
                                            id="current-password"
                                            placeholder="Current Password"
                                            required
                                            {{-- style="border-color: #fac273" --}}
                                            style="border-color: #a9cfe2"
                                          />
                                          {{-- <a id="toggleButton1" onclick="showHidePassword1()" class="btn" style="background-color: #ffbf70; --}}
                                          <a id="toggleButton1" onclick="showHidePassword1()" class="btn" style="background-color: #a9cfe2;
                                          border-color: #a9cfe2;"><i class='bi bi-eye'></i></a>
                                        </div>
                                    </div>

                                    <div class="form-password-toggle">
                                      <label class="form-label" for="new-password">New Password</label>
                                      <div class="input-group">
                                        <input
                                          type="password"
                                          name="new" 
                                          class="form-control"
                                          id="new-password"
                                          placeholder="New Password"
                                          required
                                          {{-- style="border-color: #fac273" --}}
                                          style="border-color: #a9cfe2"
                                        />
                                        <a id="toggleButton2" onclick="showHidePassword2()" class="btn" style="background-color: #a9cfe2;
                                        border-color: #a9cfe2;"><i class='bi bi-eye'></i></a>
                                      </div>
                                  </div>

                                  <div class="form-password-toggle">
                                    <label class="form-label" for="confirm-password">Confirm Password</label>
                                    <div class="input-group">
                                      <input
                                        type="password"
                                        name="password" 
                                        class="form-control"
                                        id="confirm-password"
                                        placeholder="Confirm Password"
                                        required
                                        {{-- style="border-color: #fac273" --}}
                                        style="border-color: #a9cfe2"
                                      />
                                      {{-- <a id="toggleButton3" onclick="showHidePassword3()" class="btn" style="background-color: #ffbf70; --}}
                                      <a id="toggleButton3" onclick="showHidePassword3()" class="btn" style="background-color: #a9cfe2;
                                      border-color: #a9cfe2;"><i class='bi bi-eye'></i></a>
                                    </div>
                                </div>

                                   
                                    <div class="mb-3" style="margin-top: 10px">
                                        @include('dashboard.shared.message')
                                    </div>
                                    <div class="mb-3">
                                      {{-- <button class="btn btn-warning" type="submit" style="background-color: #fac273; margin-top:5px;">Reset</button> --}}
                                      <button class="btn" type="submit" style="background-color: #a9cfe2; margin-top:5px;">Reset</button>
                                    </div>
                                  </form>

                                  <p style="margin-top: 5px; margin-left: 30%">
                                    {{-- <a href="{{url("dashboard/products")}}" style="color: #fac273">&larr; Back To Homepage</a>		</p> --}}
                                    <a href="{{url("dashboard/products")}}" style="color: #6b93a7">&larr; Back To Dashboard</a>		</p>

                                  
                            </div>
                        </div>
                    </div><!-- End -->
    
                </div>
            </div><!-- End -->
    
        </div>
    </div>
       
        


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" type="text/javascript"></script>




<script src="{{asset("assets/vendor/libs/jquery/jquery.js")}}"></script>
<script src="{{asset("assets/vendor/libs/popper/popper.js")}}"></script>
<script src="{{asset("assets/vendor/js/bootstrap.js")}}"></script>
<script src="{{asset("assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js")}}"></script>

<script src="{{asset("assets/vendor/js/menu.js")}}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{asset("assets/vendor/libs/apex-charts/apexcharts.js")}}"></script>

<!-- Main JS -->
<script src="{{asset("assets/js/main.js")}}"></script>

<!-- Page JS -->
<script src="{{asset("assets/js/dashboards-analytics.js")}}"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>


<script>
  function showHidePassword1() {
    var passwordField = document.getElementById("current-password");
    var toggleButton = document.getElementById("toggleButton1");
    
    if (passwordField.type === "password") {
      passwordField.type = "text";
      toggleButton.innerHTML = "<i class='bi bi-eye-slash'></i>";
    } else {
      passwordField.type = "password";
      toggleButton.innerHTML = "<i class='bi bi-eye'></i>";
    }
  }
</script>

<script>
  function showHidePassword2() {
    var passwordField = document.getElementById("new-password");
    var toggleButton = document.getElementById("toggleButton2");
    
    if (passwordField.type === "password") {
      passwordField.type = "text";
      toggleButton.innerHTML = "<i class='bi bi-eye-slash'></i>";
    } else {
      passwordField.type = "password";
      toggleButton.innerHTML = "<i class='bi bi-eye'></i>";
    }
  }
</script>

<script>
  function showHidePassword3() {
    var passwordField = document.getElementById("confirm-password");
    var toggleButton = document.getElementById("toggleButton3");
    
    if (passwordField.type === "password") {
      passwordField.type = "text";
      toggleButton.innerHTML = "<i class='bi bi-eye-slash'></i>";
    } else {
      passwordField.type = "password";
      toggleButton.innerHTML = "<i class='bi bi-eye'></i>";
    }
  }
</script>

</body>
</html>
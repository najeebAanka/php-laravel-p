<!--<a href="#" class="back-to-top d-flex align-items-center justify-content-center no-print">
    <i class="bi bi-arrow-up-short"></i>
</a>-->

<!-- Vendor JS Files -->
<script src="{{ url('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ url('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ url('assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ url('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ url('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ url('assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ url('assets/js/main.js') }}"></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>



{{-- ---------------summernote------------------- --}}
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js" defer></script>
{{-- ---------------------------------------------------- --}}



{{-- ---------------------Side Nav Bar---------------------------------- --}}
{{-- To add active class to sub items of collapse menu --}}
<script>
    let server = "{{ url('backend-crud/v1') }}/";

    (function() {
        var current = location.pathname;
        if (current === "") return;
        var menuItems = document.querySelectorAll('.nav-content a');
        for (var i = 0, len = menuItems.length; i < len; i++) {
            if (menuItems[i].getAttribute("href").indexOf(current) !== -1) {
                menuItems[i].className += " active";
            }
        }
    })();
</script>

{{-- To remove collapsed class off page items to appear like active --}}
<script>
    // (function() {
    //     var current_ = location.pathname;
    //     if (current_ === "") return;
    //     var menuItems_ = document.querySelectorAll('.basic-nav-item a');
    //     for (var i = 0, len = menuItems_.length; i < len; i++) {
    //         if (menuItems_[i].getAttribute("href").indexOf(current_) !== -1) {
    //             menuItems_[i].className = "nav-link";
    //         }
    //     }
    // })();

    $(".basic-nav-item a").each(function() {

        if (this.href == window.location.href) {
            this.className = "nav-link";
        }

    });
</script>

{{-- To keep collapse menu open and active if any of sub items is active --}}
<script>
    (function() {
        var menuItems__ = document.querySelectorAll('.nav-content a');
        for (var i = 0, len = menuItems__.length; i < len; i++) {
            if (menuItems__[i].classList.contains('active')) {
                menuItems__[i].parentElement.parentElement.classList.add('show');
                menuItems__[i].parentElement.parentElement.previousElementSibling.className = "nav-link";
            }
        }
    })();
</script>
{{-- -----------------------End Side Nav Bar------------------------------------- --}}

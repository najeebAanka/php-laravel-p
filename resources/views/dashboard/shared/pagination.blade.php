<div class="row">


    <div>
        {{-- <small> --}}

        <p style="margin-top: 15px;">
            <?php
            if ($data->firstItem() == $data->lastItem()) {
                echo " Page {$data->currentPage()} of {$data->lastPage()} | Item {$data->lastItem()} of {$data->total()}";
            } elseif ($data->lastItem() == 0) {
                echo '';
            } else {
                echo " Page {$data->currentPage()} of {$data->lastPage()} | Items {$data->firstItem()} to {$data->lastItem()} of {$data->total()}";
            }
            ?>
        </p>

        {{-- </small> --}}
    </div>

    <div class="demo-inline-spacing">
        <!-- Basic Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item first">
                    {{-- <a class="page-link" href="<?php echo strtok($_SERVER['REQUEST_URI'], '?') . '?page=1'; ?>" <?php if ($data->currentPage() == 1) {
                        echo 'style="pointer-events: none; cursor: default;"';
                    } ?> --}}
                    <a class="page-link" href="<?php echo strtok($_SERVER['REQUEST_URI'], '?') . '?page=1'; ?>" <?php if ($data->currentPage() == 1) {
                        echo 'hidden';
                    } ?>><i
                            class="tf-icon bx bx-chevrons-left"></i></a>
                </li>
                <li class="page-item prev">
                    <a class="page-link" href="<?php if ($data->currentPage() == 1) {
                        echo strtok($_SERVER['REQUEST_URI'], '?') . '?page=1';
                    } else {
                        echo strtok($_SERVER['REQUEST_URI'], '?') . '?page=' . $data->currentPage() - 1;
                    } ?>" <?php if ($data->currentPage() == 1) {
                        echo 'hidden';
                    } ?>><i
                            class="tf-icon bx bx-chevron-left"></i></a>
                </li>



                <li class="page-item active">
                    <a class="page-link"><?php echo $data->currentPage(); ?></a>
                </li>


                <li class="page-item next">
                    <a class="page-link" href="<?php if ($data->currentPage() != $data->lastPage()) {
                        echo strtok($_SERVER['REQUEST_URI'], '?') . '?page=' . $data->currentPage() + 1;
                    } else {
                        echo strtok($_SERVER['REQUEST_URI'], '?') . '?page=' . $data->lastPage();
                    } ?>" <?php if ($data->currentPage() == $data->lastPage()) {
                        echo 'hidden';
                    } ?>><i
                            class="tf-icon bx bx-chevron-right"></i></a>
                </li>
                <li class="page-item last">
                    <a class="page-link" href="<?php echo strtok($_SERVER['REQUEST_URI'], '?') . '?page=' . $data->lastPage(); ?>" <?php if ($data->currentPage() == $data->lastPage()) {
                        echo 'hidden';
                    } ?>><i
                            class="tf-icon bx bx-chevrons-right"></i></a>
                </li>
            </ul>
        </nav>
        <!--/ Basic Pagination -->
    </div>


</div>

<?php
session_start();
if ($_SESSION['login_valid'] != "YES") {
    ?>
    <script type="text/javascript">
        window.location = 'index.php';
    </script>
    <?php
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Students</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="shortcut icon" href="img/favicon.ico"/>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <!-- global css -->
        <link type="text/css" href="css/app.css" rel="stylesheet"/>
        <!-- end of global css -->
        <!--page level css -->
        <link rel="stylesheet" type="text/css" href="vendors/datatables/css/dataTables.bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link href="vendors/toastr/css/toastr.min.css" rel="stylesheet"/>

        <link rel="stylesheet" type="text/css" href="css/custom_css/datatables_custom.css">
        <!--end of page level css-->
        <link rel="stylesheet" type="text/css" href="vendors/sweetalert2/css/sweetalert2.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/custom_css/sweet_alert2.css">
           
    </head>

    <body class="skin-default">
        <div class="preloader">
            <div class="loader_img"><img src="img/loader.gif" alt="loading..." height="64" width="64"></div>
        </div>
        <?php
        require_once 'header.php';
        ?>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <?php
            require_once 'sidebar.php';
            ?>
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Students
                    </h1>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">
                                <i class="fa fa-fw ti-home"></i> Students
                            </a>
                        </li>

                        <li class="active">
                            All Students
                        </li>
                    </ol>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel ">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="ti-layout-grid3"></i> Students
                                    </h3>

                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="staffTbl">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Institute 
                                                    </th>
                                                    <th>
                                                        Student No
                                                    </th>
                                                    <th>
                                                        Name
                                                    </th>
                                                    <th>Gender</th>
                                                    <th>Email Address</th>
                                                    <th>Contact No</th>
                                                    <th>Date Created</th>
 <th>Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                         <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="post" id="deleteStaffForm">
                                    <div class="modal-body">
                                        <div>
                                            <p>
                                                Are you sure you want to delete.?<span class="holder" id="nameholder"></span>

                                            </p>
                                        </div>
                                        <input type="hidden" id="code" name="code"/>
                                        <input type="hidden"  name="type" value="updateInformation"/>

                                        <input type="hidden" class="form-control" name="tablename" value="students">


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                        <button type="submit"  class="btn btn-primary">YES</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="background-overlay"></div>
                </section>
                <!-- /.content -->
            </aside>
            <!-- /.right-side -->
        </div>
        <!-- ./wrapper -->
        <!-- global js -->
        <script src="js/app.js" type="text/javascript"></script>
        <!-- end of global js -->
        <!-- begining of page level js -->
        <script type="text/javascript" src="vendors/datatables/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="vendors/datatables/js/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="js/custom_js/datatables_custom.js"></script>
        <script type="text/javascript" src="vendors/sweetalert2/js/sweetalert2.min.js"></script>
           <script src="vendors/toastr/js/toastr.min.js"></script>
        <script src="vendors/iCheck/js/icheck.js"></script>
        <script type="text/javascript" src="js/custom_js/toastr_notifications.js"></script>

        <script type="text/javascript" src="js/getstudents.js"></script>

        <!-- end of page level js -->
    </body>

</html>

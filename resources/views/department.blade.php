
@extends('layouts.master')

@section('content')


<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Departments
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw ti-home"></i> Configurations
                </a>
            </li>

            <li class="active">
                Departments
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php
        $permissions = Session::get('permissions');
        
        if (in_array("CREATE_DEPARTMENT", $permissions)) {
            ?> 
            <div>
                <div class="right_aligned" style="margin-bottom: 15px;">
                    <button type="button" class="btn btn-info " data-toggle="modal" data-target="#departmentModal">
                        Add Department
                    </button>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="row">
            <div class="col-lg-12">

                <div class="panel ">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="ti-layout-grid3"></i> Departments
                        </h3>

                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="departmentTbl">
                                <thead>
                                    <tr>
                                        <th>
                                            Name
                                        </th>

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
        <div id="departmentModal" class="modal fade animated" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">New Department</h4>
                    </div>
                    <form role="form" id="saveDepartmentForm">
                        <input type="hidden" class="form-control form-control-lg input-lg"  name="_token" value="<?php echo csrf_token() ?>" />

                        <div class="modal-body">
                            <div class="row m-t-10">



                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label for="first-name">Department Name</label>
                                        <input type="text" name="name"
                                               required class="form-control m-t-10">
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Submit</button>


                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" id="deleteDepartmentForm">
                        <input type="hidden" class="form-control form-control-lg input-lg" id="token"  name="_token" value="<?php echo csrf_token() ?>" />

                        <div class="modal-body">
                            <div>
                                <p>
                                    Are you sure you want to delete.?<span class="holder" id="nameholder"></span>

                                </p>
                            </div>
                            <input type="hidden" id="code" name="code"/>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                            <button type="submit"  class="btn btn-primary">YES</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Update </h4>
                    </div>
                    <form id="updateDepartmentForm" >
                        <div class="modal-body">
                            <input type="hidden" class="form-control form-control-lg input-lg"  name="_token" value="<?php echo csrf_token() ?>" />

                            <div class="form-group">
                                <label for="region" class="control-label">Name:</label>
                                <input type="text" class="form-control" name="name" id="departmentName" required>
                            </div>

                            <input type="hidden" class="form-control"  name="code" id="id">


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>





        <div class="background-overlay"></div>
    </section>
    <!-- /.content -->
</aside>

@endsection 

@section('userjs')
<script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/custom_js/datatables_custom.js')}}"></script>
<script src="{{ asset('js/department.js')}}" type="text/javascript"></script>

@endsection
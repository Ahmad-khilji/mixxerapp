@extends('layouts1.base')
@section('title', 'Users')
@section('main', 'User Management')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Users List Table -->
            <div class="card">

                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <table class="table border-top dataTable" id="usersTable">
                            <thead>
                                <tr>           
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($user as $item)
                                    
                                <tr>
                                  <td>{{$item->first_name}}</td>
                                  <td>{{$item->last_name}}</td>
                                  <td>{{$item->email}}</td>
                                 
                                </tr>
                           @endforeach
                        </table>
                     
                    </div>
                </div>
            </div>
        </div>
    @endsection

    


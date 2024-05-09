@extends('layouts1.base')
@section('title', 'Reported reports')
@section('main', 'Report Management')
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

            <!-- reports List Table -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-3">Reported Posts</h5>
                        <div class="">
                            {{-- <button class="btn btn-primary btn-sm" id="clearFiltersBtn">Clear Filter</button> --}}
                        </div>
                    </div>



                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session()->get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                </div>
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">

                        <table class="table border-top dataTable" id="reportsTable">
                            <thead>
                                <tr>

                                    <th>User</th>
                                    <th>Reported Post</th>
                                    <th>Reason</th>
                                   
                                </tr>
                            </thead>
                            <tbody id="">
                                @foreach ($reports as $item)
                                    <tr>
                                        <td class="sorting_1">
                                            <div class="d-flex justify-content-start align-items-center user-name">

                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold user-name-text">{{ $item->user->first_name }}
                                                        {{ $item->user->last_name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="sorting_1">
                                            <div class="d-flex justify-content-start align-items-center user-name">
                                                <div class="d-flex flex-column">
                                                        <span class="fw-semibold user-name-text">{{ $item->reported_post->first_name }} {{ $item->reported_post->last_name }}</span>
                                                    
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->message }}</td>
                                       
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>



                    </div>
                </div>
           

            </div>
        </div>
    @endsection

    
  

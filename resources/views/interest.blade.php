@extends('layouts1.base')
@section('title', 'Interest')
@section('main', 'Interests Management')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Users List Table -->
            <div class="card">

                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session()->get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session()->has('edit'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session()->get('edit') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session()->has('delete'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ session()->get('delete') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row me-2 mt-4 mb-2">
                            <div class="col-md-2">
                                <div class="me-3">

                                   
                                
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div
                                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">

                                    <div class="dt-buttons btn-group flex-wrap">
                                        <button class="btn btn-secondary add-new btn-primary" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                            data-bs-target="#addNewBus"><span><i
                                                    class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                                    class="d-none d-sm-inline-block">Add Interests</span></span></button>
                                    </div>



                                </div>
                            </div>

                        </div>



                        <hr class="mb-3">

                        <div class="row">
                            <div id="accordionPayment" class="accordion">
                                <div class="row">
                                    @foreach ($interests as $item)
                                        <div class="col-md-11 mb-2">

                                            <div class="card accordion-item">

                                                
                                                       
                                                       <h4 class="accordion-body mb-0"> {{ $item->interest }}</h4>
                                                   
                                                
                                                    {{-- <div class="accordion-body">
                                                        {{ $faq->answer }}
                                                    </div>
                                                 --}}
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                                            <a data-bs-toggle="modal" data-bs-target="#edit{{ $item->id }}"
                                                class="delete-icon">
                                                <i class="bi bi-pencil-square mx-2"></i>
                                            </a>
                                            <a data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}"
                                                class="delete-icon">
                                                <i class="bi bi-trash mx-2"></i>
                                            </a>
                                            <div class="modal fade"data-bs-backdrop='static'  id="deleteModal{{ $item->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content deleteModal verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">
                                                                Are you sure you want to delete
                                                                this Interest's?
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="body">
                                                                After deleting the Interest's you will add a new
                                                                Interest's
                                                            </div>
                                                        </div>
                                                        <hr class="hr">

                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="first">
                                                                    <a href="" class="btn" data-bs-dismiss="modal"
                                                                        style="color: #a8aaae ">Cancel</a>
                                                                </div>
                                                                <div class="second">
                                                                    <a class="btn text-center"
                                                                        href="{{ route('dashboard-interest-delete', $item->id) }}">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" data-bs-backdrop='static' id="edit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Edit interest</h5>
                            
                                                        </div>
                            
                                                        <form action="{{ route('dashboard-interest-edit' ,$item->id) }}" id="" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col mb-3">
                                                                        <label for="nameWithTitle" class="form-label">interest</label>
                                                                        <input type="text" id="nameWithTitle" name="interest"
                                                                            class="form-control" value="{{ $item->interest }}" placeholder="Question" required />
                            
                                                                    </div>
                            
                                                                </div>
                                                                {{-- <div class="row">
                                                                    <div class="col mb-3">
                                                                        <label for="nameWithTitle" class="form-label">Answer</label>
                                                                        <textarea id="" name="answer"  class="form-control" rows="3" placeholder="Answer" required> {{ $faq->answer }}</textarea>
                                                                    </div>
                            
                                                                </div>
                             --}}
                            
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-label-secondary" id="closeButton" data-bs-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Edit Interest</button>
                                                            </div>
                                                        </form>
                            
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>


                            </div>

                        </div>








                    </div>
                </div>
                <!-- Offcanvas to add new user -->



                <div class="modal fade" data-bs-backdrop='static' id="addNewBus" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Add New interest</h5>

                            </div>

                            <form action="{{ route('dashboard-interest-add') }}" id="addBusForm" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="nameWithTitle" class="form-label">interest</label>
                                            <input type="text" id="nameWithTitle" name="interest"
                                                class="form-control" placeholder="interest" required />

                                        </div>

                                    </div>
                                    {{-- <div class="row">
                                        <div class="col mb-3">
                                            <label for="nameWithTitle" class="form-label">Answer</label>
                                            <textarea id="" name="answer" class="form-control" rows="3" placeholder="Answer" required></textarea>
                                        </div>

                                    </div> --}}


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" id="closeButton" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Add interest</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>




            </div>
        </div>
    @endsection
    @section('script')
        <script>
            $(document).ready(function() {
                $('#closeButton').on('click', function(e) {
                    $('#addBusForm')[0].reset();

                });

            });
        </script>
    @endsection
 

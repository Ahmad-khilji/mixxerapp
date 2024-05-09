@extends('layouts1.base')
@section('title', 'Tickets')
@section('main', 'Ticket Chat')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/css/pages/app-chat.css" />
@endsection

@section('content')

    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="app-chat card overflow-hidden">
                <div class="row g-0">
                    <!-- Chat History -->
                    <div class="col app-chat-history bg-body">
                        <div class="chat-history-wrapper">
                            <div class="chat-history-header border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex overflow-hidden align-items-center">
                                        <i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2"
                                            data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
                                        <div class="flex-shrink-0 avatar">
                                            <img src="{{ $user->profile_image != '' ? $user->profile_image : asset('placeholder.png') }}"
                                                alt="Avatar" class="rounded-circle" data-bs-toggle="sidebar" data-overlay
                                                data-target="#app-chat-sidebar-right" />
                                        </div>
                                        <div class="chat-contact-info flex-grow-1 ms-2">
                                            <h6 class="m-0">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                        </div>
                                    </div>
                                    @if ($ticket->status == 1)
                                        <a href="#" class="btn btn-primary bg-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">Close</a>
                                        <!-- Close Ticket Modal -->
                                        <div class="modal fade" data-bs-backdrop='static' id="deleteModal" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                <div class="modal-content deleteModal verifymodal">
                                                    <div class="modal-header">
                                                        <div class="modal-title" id="modalCenterTitle">Are you
                                                            sure you want to close
                                                            this ticket?
                                                        </div>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="body">After closing this ticket user cannot
                                                            send message in this ticket</div>
                                                    </div>
                                                    <hr class="hr">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="first">
                                                                <a href="#" class="btn" data-bs-dismiss="modal"
                                                                    style="color: #a8aaae ">Cancel</a>
                                                            </div>
                                                            <div class="second">
                                                                <a class="btn text-center"
                                                                    href="{{ route('dashboard-ticket-close-ticket', $ticket->id) }}">Close</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- Chat History Body -->
                            <div class="chat-history-body bg-body" id="chat-history-body">
                                <ul class="list-unstyled chat-history" id="list-unstyled">
                                    @foreach ($messages as $message)
                                        @if ($message->sendBy == 'admin')
                                            <!-- Sent Messages -->
                                            <li class="chat-message chat-message-right">
                                                <div class="d-flex overflow-hidden">
                                                    <div class="chat-message-wrapper flex-grow-1">
                                                        <div class="chat-message-text">
                                                            <p class="mb-0">{{ $message->message }}</p>
                                                        </div>
                                                        <div class="text-end text-muted mt-1">
                                                            <small>{{ date('d/m/Y, h:i:s', $message->time) }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="user-avatar flex-shrink-0 ms-3">
                                                        <div class="avatar avatar-sm">
                                                            <img src="{{ asset('placeholder.png') }}" alt="Avatar"
                                                                class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @else
                                            <!-- Received Messages -->
                                            <li class="chat-message">
                                                <div class="d-flex overflow-hidden">
                                                    <div class="user-avatar flex-shrink-0 me-3">
                                                        <div class="avatar avatar-sm">
                                                            @if ($user->profile_image)
                                                                <img src="{{ $user->profile_image }}" alt="Avatar" class="rounded-circle" />
                                                            @else
                                                                <div class="avatar-initials rounded-circle bg-primary text-white">
                                                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="chat-message-wrapper flex-grow-1">
                                                        <div class="chat-message-text">
                                                            <p class="mb-0">{{ $message->message }}</p>
                                                        </div>
                                                        <div class="text-muted mt-1">
                                                            <small>{{ date('d/m/Y, h:i:s', $message->time) }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <!-- Chat History Footer (Message Input) -->
                            @if ($ticket->status == 0)
                                <div class="chat-history-footer shadow-sm">
                                    <form class="d-flex justify-content-between align-items-center" id="messageForm"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->uuid }}">
                                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                        <input class="form-control message-input border-0 me-3 shadow-none"
                                            placeholder="Type your message here" name="message" />
                                        <div class="message-actions d-flex align-items-center">
                                            <button type="submit" class="btn btn-primary d-flex send-msg-btn"
                                                id="sendMessage">
                                                <i class="ti ti-send me-md-1 me-0" id="sendicon"></i>
                                                <span class="align-middle" id="sending">Send</span>
                                                <span class="align-middle spinner-border text-dark" style="display: none"
                                                    id="loader" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- App Overlay (for modals) -->
                    <div class="app-overlay"></div>
                </div>
            </div>
        </div>
        <!-- / Content -->
    @endsection

    @section('script')
        <!-- Page JS -->
        <script src="/assets/js/app-chat.js"></script>
        <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
        <script>
            function scrollToBottom() {
                var chatHistory = document.getElementById('chat-history-body');
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }

            $(document).ready(function() {
                $(document).on('submit', '#messageForm', function(e) {
                    e.preventDefault();
                    var loader = $('#loader');
                    var sending = $('#sending');
                    var sendicon = $('#sendicon');

                    loader.show()
                    sendicon.hide();
                    sending.hide();

                    var formData = new FormData(this);
                    $('.message-input').val('');

                    $.ajax({
                        type: "POST",
                        url: '{{ route('dashboard-ticket-send-message') }}',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            loader.hide()
                            sendicon.show();
                            sending.show();
                            console.log(response);

                            var newMessage = `
                                <li class="chat-message chat-message-right">
                                    <div class="d-flex overflow-hidden">
                                        <div class="chat-message-wrapper flex-grow-1">
                                            <div class="chat-message-text">
                                                <p class="mb-0">${response.message}</p>
                                            </div> 
                                            <div class="text-end text-muted mt-1">
                                                <small>Just now</small>
                                            </div>
                                        </div>
                                        <div class="user-avatar flex-shrink-0 ms-3">
                                            <div class="avatar avatar-sm">
                                                <img src="/assets/img/avatars/mixxer_logo.png" alt="Avatar" class="rounded-circle" />
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                `;

                            $('#list-unstyled').append(newMessage);
                            scrollToBottom();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            // Handle error here
                        }
                    });
                });
            });
        </script>
    @endsection

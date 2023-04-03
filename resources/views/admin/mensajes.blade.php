@extends('theme')
@section('title', 'Mensajes')
@section('content')
    <div class="row">
        <div class="col">
            <div class="mailbox-container">
                <div class="card">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="mailbox-list col-xl-12">
                                <ul class="list-unstyled">
                                    @foreach ($chats as $key => $chat)
                                        @php
                                            $datos = $chat->boda->datos;
                                        @endphp
                                        <li class="mailbox-list-item @if ($chat->new_messages->count() != 0) active @endif">
                                            <a href="{{ route('admin.mensajes.chat', $chat->id) }}">
                                                {{-- <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="">
                                        </div> --}}
                                                <img class="d-none d-sm-block"
                                                    src="https://www.icmetl.org/wp-content/uploads/2020/11/user-icon-human-person-sign-vector-10206693.png"
                                                    alt="">
                                                <div class="mailbox-list-item-content">
                                                    <span class="mailbox-list-item-title text-capitalize">
                                                        @if ($datos != null)
                                                            {!! $datos->nombre_1 !!} & {!! $datos->nombre_2 !!}
                                                        @endif
                                                        ({!! $chat->boda->ref !!}) <span
                                                            style="vertical-align:text-top;margin-left:10px;float: inherit!important"
                                                            class="badge rounded-pill badge-danger float-end @if ($chat->new_messages->count() == 0) hide @endif">{!! $chat->new_messages->count() !!}</span>
                                                    </span>
                                                    <p class="mailbox-list-item-text">
                                                        @if ($chat->messages != '[]')
                                                            @if ($chat->messages->last()->user_id == auth()->user()->id)
                                                                Yo:
                                                            @endif <small
                                                                style="font-size: 12px!important">{!! mb_strimwidth(strip_tags($chat->messages->last()->message), 0, 25, '...') !!}</small>
                                                        @endif
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="/assets/plugins/summernote/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('js')
    <script src="/assets/plugins/summernote/summernote-lite.min.js"></script>
    <script src="/assets/js/pages/mailbox.js"></script>
@endsection

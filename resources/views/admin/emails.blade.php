@extends('theme')

@section('title', 'Notificaciones')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Asunto</th>
                                <th>Mensaje</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mails as $key => $mail)
                            <tr>
                                <td>{!! $mail->title !!}</td>
                                <td>{!! \Str::of($mail->msg)->limit(40, '...') !!}</td>
                                <td>
                                    <a href="{{ route('admin.emails.preview', ['id' => $mail->id]) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i></a>
                                    <a href="{{ route('admin.emails.edit', ['id' => $mail->id]) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
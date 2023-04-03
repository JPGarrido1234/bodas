@if(!isset($datos))
<div class="alert alert-warning alert-style-light align-middle" style="" role="alert">
    <p class="mb-0">Es necesario que completen los datos personales para poder enviar documentos.</p>
    @csrf
    <p class="mb-0 pt-2">
        <a href="{{ route('admin.bodas.completar.notificacion', ['id' => request()->id]) }}" onclick="return confirm('El usuario recibirá un e-mail con un enlace para completar los datos restantes. ¿Estás seguro?')" class="btn btn-sm btn-primary right float-right"><i class="fas fa-envelope"></i> {{ ($boda->email_datos == null) ? 'Enviar' : 'Reenviar' }} e-mail</a>
        <button {!! tooltip('Copiar enlace para compartir') !!} class="btn btn-sm btn-primary clipboard" data-clipboard-text="{!! route('admin.bodas.completar', ['token' => $boda->token]) !!}" type="button" id="share-link1"><i class="material-icons no-m fs-5">content_copy</i> Copiar enlace</button>
    </p>
    @if($boda->email_datos != null)
        <p class="mt-2 mb-0"><small><i class="far fa-clock"></i> Último envío: {{ \Carbon\Carbon::parse($boda->email_datos)->format('d/m/Y H:i') }}</small></p>
    @endif
    <div class="input-group mt-3">
        <input style="font-size:12px;padding:10px 10px;" type="text" class="form-control form-control-solid-bordered" value="{!! route('admin.bodas.completar', ['token' => $boda->token]) !!}" aria-label="{!! route('admin.bodas.completar', ['token' => $boda->token]) !!}">
        <button class="btn btn-sm btn-primary clipboard" data-clipboard-text="{!! route('admin.bodas.completar', ['token' => $boda->token]) !!}" type="button" id="share-link1"><i class="material-icons no-m fs-5">content_copy</i></button>
    </div>
</div>
@endif
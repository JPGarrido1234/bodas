@php
    $cobros = $boda->cobros->where('status', 'pending');
@endphp

@if($cobros->count() > 0)
    <div class="col-12">
        <div class="alert alert-secondary text-center" role="alert">
            <p>Hay pagos pendientes de justificar. Accede desde el siguiente enlace para ver los que están pendientes:</p>
            <a href="{{ route('user.pagos') }}" class="btn btn-primary mt-2">Continuar</a>
        </div>
    </div>
@endif
{{-- @if($boda->date_ingreso == null && $boda->email_datos != null)
    @php
        $now = \Carbon\Carbon::now();
        $final = \Carbon\Carbon::parse($boda->email_datos)->addDays(10);
        $diff = $final->diff($now);
    @endphp
    <div class="col-12">
        <div class="alert alert-secondary text-center" role="alert">
            <p>Es necesario indicar la fecha del ingreso antes del <b>{!! $final->format('d/m/Y') !!}</b> y subir un justificante:</p>
            <button data-bs-toggle="modal" data-bs-target="#ingresoModal" class="btn btn-primary mt-2">Continuar</button>
        </div>
    </div>
    <div class="modal fade" id="ingresoModal" tabindex="-1" aria-hidden="true">
        <form action="{!! route('user.boda.update.ingreso') !!}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">Fecha ingreso</label>
                            <input required="required" name="date_ingreso" type="date" class="form-control">
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label">Comprobante de ingreso</label>
                            <input type="file" name="files" accept="image/png, image/jpeg, application/pdf" class="form-control">
                            <small class="text-muted float-end text-end mt-2">(Formatos permitidos: JPG, PNG o PDF)</small>
                        </div>
                        <input type="hidden" name="id" value="{!! $boda->id !!}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Guardar y enviar</button>
                </div>
            </div>
        </div>
        </form>
    </div>
@endif --}}
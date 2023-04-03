@extends('theme')
@section('title', 'Cobros: Añadir')

@section('content')
<form action="{{ route('cobros.add_submit') }}" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label">Boda</label>
                                    <select @if(request()->has('boda_id')) disabled="disabled" @endif required="required" name="boda_id" class="form-control linea_negocio">
                                        <option value="" selected disabled>---</option>
                                        @foreach($bodas as $boda)
                                            <option @if(request()->has('boda_id') && $boda->id == request()->boda_id) selected="selected" @endif value="{!! $boda->id !!}">{!! $boda->name !!}</option>
                                        @endforeach
                                    </select>
                                    @if(request()->has('boda_id'))
                                    <input value="{{ request()->boda_id }}" type="hidden" name="boda_id">
                                    @endif
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Tipo</label>
                                    <select required="required" name="type" class="form-control linea_negocio">
                                        @foreach (filterTypeFacture(request()->boda_id) as $tipos)
                                            @foreach($tipos as $item => $value)
                                                <option value="{{ $item }}">{{ $value }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Concepto</label>
                                    <input type="text" class="form-control" name="concepto">
                                </div>
                                {{-- <div class="col-sm-12"> 
                                    <label class="form-label">Justificante (opcional)</label>
                                    <input type="file" name="files" accept="image/png, image/jpeg, application/pdf" class="form-control">
                                    <small class="text-muted float-end text-end mt-2">(Formatos permitidos: JPG, PNG o PDF)</small>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label">Total (€)</label>
                                    <input name="total" type="number" class="form-control" placeholder="Importe">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Estado</label>
                                    <select name="status" id="" class="form-control">
                                        <option value="pending">Pendiente</option>
                                        <option value="completed">Completado</option>
                                    </select>
                                </div>
                                {{-- <div class="col-sm-12">
                                    <label class="form-label">Fecha ingreso (opcional)</label>
                                    <input type="date" name="date" class="form-control">
                                </div> --}}
                                <div class="col-12 mt-5">
                                    <div class="form-check">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="notificacion" type="checkbox" id="notificacion">
                                            <label class="form-check-label" for="notificacion">Notificación por e-mail</label>
                                          </div>
                                        <small>El usuario recibirá un enlace para adjuntar el comprobante.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <button class="btn btn-primary float-end" type="submit"><i class="material-icons">send</i>Enviar cobro</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('css')
<link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="/assets/plugins/datatables/datatables.min.js"></script>
<script src="/assets/js/pages/datatables.js"></script>
@endsection
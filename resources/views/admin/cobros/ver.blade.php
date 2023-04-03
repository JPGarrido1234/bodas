@extends('theme')
@section('title', ((user()->rol == 'user') ? 'Pagos' : 'Cobros') . ': '.tiposFactura()[$factura->type] )
@section('content')
<div class="row">
        @if(user()->rol != 'user')
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('cobros.edit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="row">
                                    @if(user()->rol != 'user')
                                    <div class="col-12">
                                        <label class="form-label">Boda</label>
                                        <input type="text" class="form-control" disabled="disabled" value="{{ $factura->boda->name }}">
                                        @if(request()->has('boda_id'))
                                        <input value="{{ request()->boda_id }}" type="hidden" name="boda_id">
                                        @endif
                                    </div>
                                    @endif
                                    <div class="col-12">
                                        <label class="form-label">Tipo</label>
                                        <select disabled="disabled" required="required" class="form-control linea_negocio">
                                            @foreach (tiposFactura() as $key => $tipo)
                                                <option value="{{ $key }}">{{ tiposFactura()[$factura->type] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Concepto</label>
                                        <input @if(user()->rol == 'user') disabled @endif value="{{ $factura->concepto }}" type="text" class="form-control" name="concepto">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">Total (€)</label>
                                        <input @if(user()->rol == 'user') disabled="disabled" @endif value="{{ $factura->total }}" name="total" type="number" class="form-control" placeholder="Importe">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Estado</label>
                                        <select name="status" id="" class="form-control">
                                            @foreach (estadosFactura() as $key => $estado)
                                                <option value="{{ $key }}" @if($key == $factura->status) selected="selected" @endif>{{ $estado }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Notificación por e-mail</label>
                                        <br>
                                        <button class="btn btn-primary btn-sm"><i class="fas fa-envelope"></i> Enviar notificación</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary float-end" type="submit">Guardar cambios</button>
                            </div>
                        </div>
                        <input type="hidden" value="{{ $factura->id }}" name="id">
                    </form>
                </div>
            </div>
        </div>
        @else
        <div class="col-12">
            <div class="card">
                <div class="card-body lh-lg">
                    <div class="fw-light text-uppercase">{{ tiposFactura()[$factura->type] }}</div>
                    <strong>Concepto: </strong>{{ $factura->concepto ?? $factura->boda->concepto }}
                    <br>
                    <strong>Importe: </strong>{{ $factura->total }} €
                    <br>
                    <strong>Estado: <span style="font-size:12px" class="ms-2 badge @if($factura->status == 'completed') badge-success @elseif($factura->status == 'canceled') badge-danger @else badge-light @endif">{{ $factura->estado }}</span></strong>
                </div>
            </div>
        </div>
        @endif
        {{-- <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Justificante bancario <hr class="mb-0"></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <label class="form-label">Fecha ingreso</label>
                            <input @if(user()->rol == 'user') required="required" @endif type="date" name="date" class="form-control" value="{{ $factura->date ?? '' }}">
                        </div>
                        <div class="col-sm-12 col-md-6"> 
                            <label class="form-label">Justificante</label>
                            @if($factura->justificante == null)
                                <input @if(user()->rol == 'user') required="required" @endif type="file" name="files" accept="image/png, image/jpeg, application/pdf" class="form-control">
                                <small class="text-muted float-end text-end mt-2">(Formatos permitidos: JPG, PNG o PDF)</small>
                            @else
                                <a href="{{ $factura->justificante }}" class="btn btn-light"><i class="fas fa-eye"></i> Visualizar</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Lista de cobros</div>
                </div>
                <div class="card-body">
                    @if($factura->justificantes != null)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha ingreso</th>
                                    <th>Importe (€)</th>
                                    <th>Porcentaje (%)</th>
                                    <th>Datos facturación</th>
                                    <th>Justificante</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($factura->justificantes as $justificante)
                                    <tr>
                                        <td>{{ $justificante->id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($justificante->date)->format('d/m/Y') }}</td>
                                        <td>{{ $justificante->amount }}€</td>
                                        <td>{{ $justificante->percentage }} %</td>
                                        <td><button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#datos-{{ $justificante->id }}">Mostrar datos</button></td>
                                        <td>
                                            @if($justificante->url != null)
                                                <a target="_blank" class="btn btn-sm btn-primary btn-block" href="{{ $justificante->url }}">Ver justificante</a>
                                            @else
                                                <button class="btn btn-sm btn-light disabled">No se encuentra</button>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="datos-{{ $justificante->id }}" class="collapse" colspan="6">
                                            <div class="row mb-4">
                                                <div class="col-md-12 col-lg-3">
                                                    <div class="">
                                                        <label for="" class="form-label">Nombre</label>
                                                        <span class="d-block">{{ $justificante->name }}</span>
                                                    </div>
                                                    <div class="">
                                                        <label for="" class="form-label">E-mail</label>
                                                        <span class="d-block">{{ $justificante->email }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-lg-3">
                                                    <div class="">
                                                        <label for="" class="form-label">NIF</label>
                                                        <span class="d-block">{{ $justificante->nif }}</span>
                                                    </div>
                                                    <div class="">
                                                        <label for="" class="form-label">Teléfono</label>
                                                        <span class="d-block">{{ $justificante->tlf }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-lg-3">
                                                    <div class="">
                                                        <label for="" class="form-label">Dirección</label>
                                                        <span class="d-block">{{ $justificante->name }}</span>
                                                    </div>
                                                    <div class="">
                                                        <label for="" class="form-label">Ciudad/Provincia</label>
                                                        <span class="d-block">{{ $justificante->city }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-lg-3">
                                                    <div class="">
                                                        <label for="" class="form-label">País</label>
                                                        <span class="d-block">{{ $justificante->country }}</span>
                                                    </div>
                                                    <div class="">
                                                        <label for="" class="form-label">Código Postal</label>
                                                        <span class="d-block">{{ $justificante->cp }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No existen cobros registrados.</p>
                    @endif
                </div>
            </div>
        </div> --}}
        @if($factura->justificante == null)
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Añadir justificante <hr class="mb-0"></div>
                </div>
                <div class="card-body">
                    <form action="{{ route('cobros.add_justificante') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="cobro" value="{{ $factura }}">
                        <div class="row">
                            {{-- <div class="col-sm-12 col-md-9">
                                <label for="" class="form-label">Nombre completo / Razón social</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="col-sn-12 col-md-3">
                                <label for="" class="form-label">NIF</label>
                                <input type="text" class="form-control" name="nif">
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="" class="form-label">Dirección facturación</label>
                                <input type="text" class="form-control" name="address">
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label for="" class="form-label">Provincia</label>
                                <input type="text" class="form-control" name="city">
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label for="" class="form-label">País</label>
                                <input type="text" class="form-control" name="country">
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label for="" class="form-label">Código postal</label>
                                <input type="text" class="form-control" name="cp">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="" class="form-label">E-mail</label>
                                <input type="text" class="form-control" name="email">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="tlf">
                            </div> --}}
                            
                            <div class="col-sm-12 col-md-3">
                                <label class="form-label">Fecha ingreso</label>
                                <input @if(user()->rol == 'user') required="required" @endif type="date" name="date" class="form-control datepick" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-1"></div>
                            {{-- <div class="col-sm-12 col-md-3">
                                <label for="" class="form-label">Importe (€)</label>
                                <input type="number" class="form-control" name="amount">
                            </div> --}}
                            <div class="col-sm-12 col-md-5 mb-3"> 
                                <label class="form-label">Justificante</label>
                                <input @if(user()->rol == 'user') required="required" @endif type="file" name="files" accept="image/png, image/jpeg, application/pdf" class="form-control">
                                <small class="text-muted float-end text-end mt-2">(Formatos permitidos: JPG, PNG o PDF)</small>
                            </div>
                            <div class="col-sm-12 col-md-3 text-right d-flex align-self-center justify-content-center">
                                <button class="btn btn-primary float-end" type="submit"><i class="material-icons">upload</i> Guardar y enviar</button>
                            </div>
                        </div>
                        <input type="hidden" name="cobro_id" value="{{ $factura->id }}">
                    </form>
                </div>
            </div>
        </div>
        @endif
        @if($factura->status == 'completed')
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Justificante <hr class="mb-0"></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label for="" class="form-label">Fecha ingreso</label>
                                <input disabled="disabled" class="form-control disabled" type="date" name="" id="" value="{{ \Carbon\Carbon::parse($factura->date)->format('Y-m-d') }}">
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label for="" class="form-label">Justificante</label><br>
                                <a href="{{ $factura->justificante }}" target="_blank" class="btn btn-light"><i style="font-size:14px" class="far fa-file me-2"></i> Ver documento</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- <div class="col-12">
            <button class="btn btn-outline-primary btn-full" style="width: 100%;padding: 15px;"><i class="fas fa-plus"></i> AÑADIR DATOS</button>
        </div> --}}
        <div class="col-12">
            
        </div>
        <input type="hidden" name="id" value="{{ $factura->id }}">
    </form>
</div>
@endsection

@section('css')
<link href="/assets/plugins/datatables/datatables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="/assets/plugins/datatables/datatables.min.js"></script>
<script src="/assets/js/pages/datatables.js"></script>

<script>
    $('.form-range, .range-amount').on('input change', function () {
        $(this).parent('.percentage').find('.range-amount').val($(this).val());
        $(this).parent('.percentage').find('.form-range').val($(this).val());
    });
</script>
@endsection
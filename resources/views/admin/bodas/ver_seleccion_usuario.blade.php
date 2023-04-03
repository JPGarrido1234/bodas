@extends('theme')

@section('title', $boda->name . ' - Seleccion usuario' ?? $boda->ref)

@section('header-buttons')
    @if(user()->rol == 'user')
        <a class="btn btn-outline-primary" href="{!! route('user.gastronomia') !!}"><i class="material-icons-outlined">arrow_back</i> Volver</a>
    @else
        <a class="btn btn-outline-primary" href="{!! route('admin.bodas.ver', ['id' => $boda->id]) !!}"><i class="material-icons-outlined">arrow_back</i> Volver</a>
        <a class="btn btn-primary" href="{!! route('admin.og.imprimir', ['id_seleccion' => $seleccion->id, 'id' => $boda->id]) !!}"><i class="fas fa-print"></i> Imprimir</a>
    @endif
@endsection

@section('content')
    <style>
        button.nav-link {
            display: flex;
            vertical-align: middle;
        }

        button.nav-link i {
            font-size: 20px;
            padding-right: 5px;
        }

        #btns .btn {
            border-radius: 10px;
        }

        select.nacionalidad:invalid {
            color: #9f9f9f;
        }

        select.nacionalidad option:not(:first-child) {
            color: black;
        }

        .select2-container {
            width: 100% !important;
        }

        .flag-icon {
            margin-right: 7px !important;
        }

    </style>
  
    <div class="row">
        {{-- @foreach ($categorias_productos as $category)
            <div class="col-6">
                <div class="card widget widget-list" style="height: calc(100% - 20px);">
                    <div class="card-header mb-2">
                        <h5 class="card-title text-uppercase">{{ $category->nombre }}</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($category->productos as $product)
                                <li class="list-group-item">
                                    <div class="d-flex gap-2">
                                        @if (isset(unserialize($seleccion->selecciones)[$category]) && in_array($product, unserialize($seleccion->selecciones)[$category])) 
                                        @else
                                        @endif
                                        @if (isset(unserialize($seleccion->seleccion_usuario->selecciones)[$category]) && in_array($product, unserialize($seleccion->seleccion_usuario->selecciones)[$category])) 
                                        @else
                                        @endif
                                        <input id="producto_{{ $product->nombre }}" name="selecciones[{{ $category->id }}][]" value="{{ $product->id }}" class="form-check-input me-1" type="checkbox" value="" aria-label="...">
                                        {{ $product->nombre }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach --}}
        @foreach (unserialize($grupo->value) as $indice => $item)

        
            <h4 class="mt-2">{!! $item['name'] !!} @if(isset($item['block']) && $item['block'] != null) (Selección bloqueada) @endif @if($item['limite'] != null) <div class="text-muted mt-2" style="font-size:14px">Límite {{ $item['limite'] }} platos</div> @endif</h4>
            @foreach($item['id_categoria'] as $key => $cat)
            @php $category = \App\Models\Categorias_oferta_gastronomica::find($cat); @endphp
            <div class="col-sm-12 col-md-4 mt-2">
                <div class="card widget widget-list" style="height: calc(100% - 20px);">
                    <div class="card-header mb-2">
                        <h5 class="card-title text-uppercase">{!! $category->nombre !!}</h5>
                    </div>
                    <div class="card-body">
                        <h4>{!! $category->name !!}</h4>
                        <div class="col-12">
                            <ul class="list-group">
                                @foreach ($category->productos as $product)
                                    @if(array_key_exists($cat, $selecciones_com))
                                        <li class="list-group-item">
                                            <div class="d-flex gap-2" style="font-size:11px">
                                                @if(in_array($product->id, $selecciones))
                                                    @if(isset($item['block']) && $item['block'] != null)
                                                        <i class="fas fa-lock text-muted"></i>
                                                    @else
                                                        <span class="material-icons-outlined  me-1 text-success" style="font-size:17px">
                                                            done
                                                        </span>
                                                    @endif
                                                @else
                                                <span class="material-icons-outlined  me-1 text-success" style="visibility: hidden">
                                                    done
                                                </span>
                                                @endif
                                                {{ $product->nombre }}
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>
@endsection
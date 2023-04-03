@extends('front')

@section('title', ' Menú - ' . $boda->name)

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
    @php
        $array = [6,7,8,9,10,11];
        $grupo = $boda->grupoOfertas;
    @endphp
    <div class="row">
        @foreach (unserialize($grupo->value) as $indice => $item)
            <h3 class="mt-2">{!! $item['name'] !!} {{-- @if(isset($item['block']) && $item['block'] != null) (Seleccion obligatoria) @endif @if($item['limite'] != null) (Límite {{ $item['limite'] }} platos) @endif --}}</h3>
            @foreach($item['id_categoria'] as $key => $cat)
            @php $category = \App\Models\Categorias_oferta_gastronomica::find($cat); @endphp
            <div class="col-sm-12 col-md-6">
                <div class="card widget widget-list">
                    <div class="card-header">
                        <h4 class="card-title text-uppercase">{!! $category->nombre !!} @if(isset($item['block'])) <i class="fas fa-lock"></i> @endif</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($category->productos as $product)
                                <li class="list-group-item">
                                    <div class="d-flex gap-2">
                                        @if(in_array($product->id, $selecciones))
                                        {{-- <span class="material-icons-outlined  me-1 text-success">
                                            done
                                        </span>
                                        @else
                                        <span class="material-icons-outlined  me-1 text-success" style="visibility: hidden">
                                            done
                                        </span> --}}
                                        <span style="font-weight:600">{{ Str::ucfirst($product->nombre) }}</span>
                                        @else
                                        <span style="color:rgb(155, 155, 155)">{{ Str::ucfirst($product->nombre) }}</span>
                                        @endif
                                        
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>
@endsection

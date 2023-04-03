<ul class="list-group list-group-flush pl-0" style="font-size:14px">
    <li class="list-group-item">
        <label class="form-label mt-0 text-dark">Nombre completo / Entidad</label>
        <input value="{{ $datos->name ?? '' }}" type="text" placeholder="Nombre completo / Entidad" name="name" class="form-control form-control-sm">
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <label class="form-label mt-0 text-dark">Documento fiscal</label>
                <input value="{{ $datos->nif ?? '' }}" type="text" placeholder="Documento fiscal" name="nif" class="form-control form-control-sm">
            </div>
            <div class="col-sm-12 col-md-6">
                <label class="form-label mt-0 text-dark">Teléfono</label>
                <input value="{{ $datos->tlf ?? '' }}" type="text" placeholder="Teléfono" name="tlf" class="form-control form-control-sm">
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <label class="form-label mt-0 text-dark">Dirección</label>
                <input value="{{ $datos->address ?? '' }}" type="text" placeholder="Dirección" name="address" class="form-control form-control-sm">
            </div>
            <div class="col-sm-12 col-md-4">
                <label class="form-label mt-0 text-dark">Código postal</label>
                <input value="{{ $datos->cp ?? '' }}" type="text" placeholder="Código postal" name="cp" class="form-control form-control-sm">
            </div>
        </div>
    </li>
    <div class="list-group-item">
        <div class="row">
            <div class="col-sm-12 col-md-7">
                <label class="form-label mt-0 text-dark">Ciudad</label>
                <input value="{{ $datos->city ?? '' }}" type="text" placeholder="Ciudad" name="city" class="form-control form-control-sm">
            </div>
            <div class="col-sm-12 col-md-5">
                <label class="form-label mt-0 text-dark">País</label>
                <input value="{{ $datos->country ?? '' }}" type="text" placeholder="País" name="country" class="form-control form-control-sm">
            </div>
        </div>
    </div>
    <li class="list-group-item">
        <label class="form-label mt-0 text-dark">Correo electrónico</label>
        <input value="{{ $datos->email ?? '' }}" type="text" placeholder="Correo electrónico" name="email" class="form-control form-control-sm">
    </li>
    <li class="list-group-item">
        <label class="form-label mt-0 text-dark">Porcentaje de facturación</label>
        <div class="form-group d-flex percentage">
            <input type="range" class="form-range mt-3" name="perc" min="0" max="100" value="{{ $datos->percentage ?? '100' }}" step="5">
            <input value="{{ $datos->percentage ?? '100' }}" type="number" name="percentage" style="width:35%;margin-left:30px" class="form-control range-amount float-end" min="0" max="100" value="100">
        </div>
    </li>
</ul>
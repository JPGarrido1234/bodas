@extends('theme')
@section('title', 'Notas')
@section('content')
<div class="row">
    <div class="col">
        <div class="card todo-container">
            <div class="row">
                <div class="col-xl-4 col-xxl-3">
                    <div class="todo-menu">

                        <h5 class="todo-menu-title">Estado</h5>
                        <ul class="list-unstyled todo-status-filter">
                            <li><a href="#" class="active"><i class="material-icons-outlined">format_list_bulleted</i>Todas</a></li>
                            <li><a href="#"><i class="material-icons-outlined">done</i>Completado</a></li>
                            <li><a href="#"><i class="material-icons-outlined">pending</i>En proceso</a></li>
                            <li><a href="#"><i class="material-icons-outlined">delete</i>Eliminado</a></li>
                        </ul>
                        <a href="#" class="btn btn-primary d-block m-b-lg">Añadir nueva</a>
                        
                        <h5 class="todo-menu-title">Buscar</h5>
                        <input type="text" class="form-control form-control-solid m-b-lg" placeholder="Buscar..">

                        <h5 class="todo-menu-title">Tags</h5>
                        <div class="todo-label-filter m-b-lg">
                            <a href="#" class="badge badge-style-light rounded-pill badge-light">general</a>
                            <a href="#" class="badge badge-style-light rounded-pill badge-primary">proyectos</a>
                            <a href="#" class="badge badge-style-light rounded-pill badge-secondary">personal</a>
                            <a href="#" class="badge badge-style-light rounded-pill badge-danger">urgente</a>
                            <a href="#" class="badge badge-style-light rounded-pill badge-warning">otros</a>
                        </div>
                        <h5 class="todo-menu-title">Preferencias</h5>
                        <div class="todo-preferences-filter">
                            <div class="todo-preferences-item">
                                <input class="form-check-input" type="checkbox" value="" id="createdByMeCheck">
                                <label class="form-check-label" for="createdByMeCheck">
                                    Creadas por mi
                                </label>
                            </div>
                            <div class="todo-preferences-item">
                                <input class="form-check-input" type="checkbox" value="" id="withoutDeadlineCheck">
                                <label class="form-check-label" for="withoutDeadlineCheck">
                                    Por los demás
                                </label>
                            </div>
                            <div class="todo-preferences-item">
                                <input class="form-check-input" type="checkbox" value="" id="highPriorityCheck" checked="">
                                <label class="form-check-label" for="highPriorityCheck">
                                    Urgentes
                                </label>
                            </div>
                            <div class="todo-preferences-item">
                                <input class="form-check-input" type="checkbox" value="" id="recentActivity">
                                <label class="form-check-label" for="recentActivity">
                                    Recientes
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-xxl-9">
                    <div class="todo-list">
                        <ul class="list-unstyled">
                            <li class="todo-item">
                                <div class="todo-item-content">
                                    <span class="todo-item-title">Terminar informes<span class="badge badge-style-light rounded-pill badge-danger">urgente</span><span class="badge badge-style-bordered badge-primary">EN PROCESO</span></span>
                                    <span class="todo-item-subtitle">Vivamus pharetra massa vitae elit pellentesque, sit amet convallis purus euismod</span>
                                </div>
                                <div class="todo-item-actions">
                                    <a href="#" class="todo-item-delete"><i class="material-icons-outlined no-m">close</i></a>
                                    <a href="#" class="todo-item-done"><i class="material-icons-outlined no-m">done</i></a>
                                </div>
                            </li>
                            <li class="todo-item">
                                <div class="todo-item-content">
                                    <span class="todo-item-title">Responder e-mails<span class="badge badge-style-light rounded-pill badge-primary">proyectos</span></span>
                                    <span class="todo-item-subtitle">Vestibulum ipsum nunc, lacinia sit amet egestas vitae, molestie quis nisi. Maecenas mi urna, ultricies non est a, commodo suscipit velit. Nullam tincidunt, magna sed scelerisque varius</span>
                                </div>
                                <div class="todo-item-actions">
                                    <a href="#" class="todo-item-delete"><i class="material-icons-outlined no-m">close</i></a>
                                    <a href="#" class="todo-item-done"><i class="material-icons-outlined no-m">done</i></a>
                                </div>
                            </li>
                            {{-- <li class="todo-item">
                                <div class="todo-item-content">
                                    <span class="todo-item-title">Buy Grocery<span class="badge badge-style-light rounded-pill badge-secondary">family</span><span class="badge badge-style-light rounded-pill badge-success">personal</span></span>
                                    <span class="todo-item-subtitle">Suspendisse nisl eros, mattis a mi id, vestibulum egestas enim. Aenean scelerisque quis metus eget varius.</span>
                                </div>
                                <div class="todo-item-actions">
                                    <a href="#" class="todo-item-delete"><i class="material-icons-outlined no-m">close</i></a>
                                    <a href="#" class="todo-item-done"><i class="material-icons-outlined no-m">done</i></a>
                                </div>
                            </li>
                            <li class="todo-item">
                                <div class="todo-item-content">
                                    <span class="todo-item-title">Update localization files for French<span class="badge badge-style-light rounded-pill badge-primary">work</span></span>
                                    <span class="todo-item-subtitle">Aliquam sit amet diam feugiat, maximus magna quis, laoreet neque. Praesent consequat eros vel risus semper, a aliquam leo tempus.</span>
                                </div>
                                <div class="todo-item-actions">
                                    <a href="#" class="todo-item-delete"><i class="material-icons-outlined no-m">close</i></a>
                                    <a href="#" class="todo-item-done"><i class="material-icons-outlined no-m">done</i></a>
                                </div>
                            </li>
                            <li class="todo-item">
                                <div class="todo-item-content">
                                    <span class="todo-item-title">Build new project on python<span class="badge badge-style-light rounded-pill badge-danger">education</span><span class="badge badge-style-light rounded-pill badge-info">side projects</span></span>
                                    <span class="todo-item-subtitle">Nam non felis id nulla interdum porta. Integer urna enim.</span>
                                </div>
                                <div class="todo-item-actions">
                                    <a href="#" class="todo-item-delete"><i class="material-icons-outlined no-m">close</i></a>
                                    <a href="#" class="todo-item-done"><i class="material-icons-outlined no-m">done</i></a>
                                </div>
                            </li>
                            <li class="todo-item">
                                <div class="todo-item-content">
                                    <span class="todo-item-title">Get laptop fixed<span class="badge badge-style-light rounded-pill badge-warning">other</span></span>
                                    <span class="todo-item-subtitle">Praesent congue lacus vel eros finibus sagittis. Mauris quis nulla molestie, convallis metus sit amet.</span>
                                </div>
                                <div class="todo-item-actions">
                                    <a href="#" class="todo-item-delete"><i class="material-icons-outlined no-m">close</i></a>
                                    <a href="#" class="todo-item-done"><i class="material-icons-outlined no-m">done</i></a>
                                </div>
                            </li>
                            <li class="todo-item">
                                <div class="todo-item-content">
                                    <span class="todo-item-title">Workout with Jane<span class="badge badge-style-light rounded-pill badge-success">personal</span></span>
                                    <span class="todo-item-subtitle">Praesent ultricies nec arcu in vestibulum. Maecenas dapibus, ex quis bibendum.</span>
                                </div>
                                <div class="todo-item-actions">
                                    <a href="#" class="todo-item-delete"><i class="material-icons-outlined no-m">close</i></a>
                                    <a href="#" class="todo-item-done"><i class="material-icons-outlined no-m">done</i></a>
                                </div>
                            </li>
                            <li class="todo-item">
                                <div class="todo-item-content">
                                    <span class="todo-item-title">Research new feature for app<span class="badge badge-style-light rounded-pill badge-primary">work</span></span>
                                    <span class="todo-item-subtitle">Cras quis massa a eros scelerisque venenatis. Etiam eget dui et metus vulputate consectetur.</span>
                                </div>
                                <div class="todo-item-actions">
                                    <a href="#" class="todo-item-delete"><i class="material-icons-outlined no-m">close</i></a>
                                    <a href="#" class="todo-item-done"><i class="material-icons-outlined no-m">done</i></a>
                                </div>
                            </li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Boda;
use App\Models\Invitado;
use App\Mail\Notif;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        switch (Auth::user()->rol) {
            case 'admin':
                return redirect(route('admin'));
                break;

            case 'com':
                return redirect(route('com'));
                break;

            case 'user':
                return redirect(route('admin'));
                break;

            default:
                return redirect(route('login'));
                break;
        }
    } else {
        return redirect(route('login'));
    }
})->name('root');

Route::get('auth/{id}', function ($id) {
    \Auth::loginUsingId($id);
    return redirect('/');
});

Route::get('mails', function () {
    $mails = \App\Models\Mail::first();
    $fecha = 'asd';

    return view('mails.template')->with(compact('mails', 'fecha'));
});

Route::get('offline', function () {
    return view('offline');
});

Route::get('soporte', function () {
    return view('soporte');
})->name('soporte');

Route::post('soporte/enviar', function () {
    return redirect()->back();
})->name('soporte.enviar');

Route::post('request-info', function (Request $request) {
    if (in_array(null, $request->except('msg'), true)) {
        return redirect()->back();
    }
    \DB::table('request_info')->insert($request->except('_token'));
    return redirect()->back()->withSuccess('true');
})->name('request-info');

Route::get('cache', function () {
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('optimize:clear');

    return 'ok';
});

Route::get('planner/{token}/{mesa_id?}', 'UserController@mesas_datos')->name('planificador.datos');
Route::post('planner/update', 'UserController@invitados_mesa_update')->name('planificador.update');

Route::group(['prefix' => 'cobros'], function() {
    Route::get('/', 'CobrosController@index')->name('cobros');
    Route::get('create', 'CobrosController@create')->name('cobros.create');
    Route::post('save', 'CobrosController@save')->name('cobros.save');
    Route::post('edit', 'CobrosController@edit')->name('cobros.edit');
    Route::get('add', 'CobrosController@add')->name('cobros.add');
    Route::post('add_submit', 'CobrosController@add_submit')->name('cobros.add_submit');
    Route::post('add_justificante', 'CobrosController@add_justificante')->name('cobros.add_justificante');
    Route::get('{id}', 'CobrosController@justificante')->name('cobros.ver');
    Route::get('notificacion/{boda_id}', 'CobrosController@notificacion')->name('cobros.notificacion');
});

Route::group(['prefix' => 'facturacion'], function() {
    Route::get('/', 'FacturacionController@index')->name('facturacion');
    Route::prefix('data')->group(function() {
        Route::get('/', 'FacturacionController@index_datafacturacion')->name('facturacion.data');
        Route::get('create', 'FacturacionController@create_datafacturacion')->name('facturacion.data.create');
        Route::post('save', 'FacturacionController@save_datafacturacion')->name('facturacion.data.save');
        Route::get('{id}', 'FacturacionController@ver_datafacturacion')->name('facturacion.data.ver');
        Route::post('delete', 'FacturacionController@delete_datafacturacion')->name('facturacion.data.delete');
    });
});

// Route::group(['prefix' => 'facturacion'], function() {
//     Route::get('/', 'FacturacionController@index')->name('facturacion');
//     Route::get('create', 'FacturacionController@create')->name('facturacion.create');
//     Route::post('save', 'FacturacionController@save')->name('facturacion.save');
//     Route::post('edit', 'FacturacionController@edit')->name('facturacion.edit');

//     Route::get('add', 'FacturacionController@add_cobro')->name('facturacion.add_cobro');
//     Route::post('add_submit', 'FacturacionController@add_cobro_submit')->name('facturacion.add_submit');

//     Route::get('{id}', 'FacturacionController@justificante')->name('facturacion.ver');
// });


/*** ADMINISTRADOR ***/
Route::group(['prefix' => 'admin', 'middleware' => 'role'], function () {
    Route::get('/', 'AdminController@resumen')->name('admin');
    Route::get('bodas', 'AdminController@bodas')->name('admin.bodas');
    Route::get('mensajes', 'AdminController@mensajes')->name('admin.mensajes');
    Route::post('subir', 'AdminController@documento')->name('admin.documento');
    Route::get('elimina/{file_name}', 'AdminController@elimina_documento')->name('admin.elimina.documento');
    Route::get('download/{file_name}', 'AdminController@download');
    Route::get('documentos', 'AdminController@documentos')->name('admin.documentos');
    Route::get('calendario', 'AdminController@calendario')->name('admin.calendario');
    Route::get('notas', 'AdminController@notas')->name('admin.notas');

    Route::group(['prefix' => 'bodas'], function () {
        Route::get('crear', 'AdminController@bodas_crear')->name('admin.bodas.crear');
        Route::post('crear', 'AdminController@bodas_crear_enviar')->name('admin.bodas.crear.enviar');
        Route::get('ver/{id}', 'AdminController@bodas_ver')->name('admin.bodas.ver');
        Route::post('cambioestado', 'AdminController@cambio_estado')->name('admin.bodas.cambio');
        Route::post('editar', 'AdminController@bodas_editar')->name('admin.bodas.editar');
        Route::post('editarpersonaldata', 'AdminController@bodas_editarpersonaldata')->name('admin.bodas.editarpersonaldata');
        Route::group(['prefix' => '{id}'], function () {
            Route::get('notificacion-completar', 'AdminController@notificacion_completar')->name('admin.bodas.completar.notificacion');
            Route::get('enviar-doc/{doc_id}', 'AdminController@bodas_doc_borrador')->name('admin.bodas.doc.borrador');
            Route::post('enviar-oferta', 'AdminController@enviar_oferta_gastronomica')->name('admin.bodas.enviar_oferta');
            Route::get('enviar-oferta-final', 'AdminController@enviar_oferta_final_gastronomica')->name('admin.bodas.enviar_oferta_final');
            Route::get('reenviar-oferta', 'AdminController@reenviar_oferta_gastronomica')->name('admin.bodas.reenviar_oferta');
            Route::get('nueva-oferta', 'AdminController@nueva_oferta_gastronomica')->name('admin.bodas.nueva_oferta');
            Route::get('ofertas-gastronomicas/{id_seleccion}/ver', 'AdminController@ver_seleccion_usuario_oferta')->name('admin.bodas.ver_seleccion_usuario_oferta');
            Route::get('datos/{doc}', 'AdminController@bodas_doc_datos')->name('admin.bodas.doc.datos');
            Route::post('datos/{doc}/enviar', 'AdminController@bodas_doc_datos_enviar')->name('admin.documentos.values.enviar');
        });
        Route::get('{id}/mesas/imprimir', 'AdminController@mesas_imprimir')->name('admin.mesas.imprimir');
        Route::get('{id}/og/imprimir', 'AdminController@OG_seleccion_imprimir')->name('admin.og.imprimir');

        Route::post('enviar-doc', 'AdminController@bodas_doc_enviar')->name('admin.bodas.doc.enviar');
        Route::get('enviar-doc-otros', 'AdminController@bodas_doc_enviar_otros')->name('admin.bodas.doc.enviar.otros');
        Route::post('editar/plano', 'AdminController@bodas_editar_plano')->name('admin.bodas.editar.plano');
        Route::post('editar/grupo-ofertas', 'AdminController@bodas_editar_grupo_ofertas')->name('admin.bodas.editar.grupo_ofertas');
        
    });

    Route::group(['prefix' => 'mensajes'], function () {
        Route::post('enviar', 'AdminController@mensajes_enviar')->name('admin.mensajes.enviar');
        Route::get('{id}', 'AdminController@mensajes_chat')->name('admin.mensajes.chat');
        Route::get('borrar/{id}', 'AdminController@mensajes_borrar')->name('admin.mensajes.borrar');
    });

    Route::group(['prefix' => 'documentos'], function () {
        Route::get('ver/{id}', 'AdminController@documentos_ver')->name('admin.documentos.ver');
        Route::post('subir', 'AdminController@documentos_subir')->name('admin.documentos.subir');
        Route::post('editar', 'AdminController@documentos_editar')->name('admin.documentos.editar');
        Route::get('editar/campos/{id}', 'AdminController@documentos_campos')->name('admin.documentos.campos');
        Route::post('editar/campos/enviar', 'AdminController@documentos_campos_enviar')->name('admin.documentos.campos.enviar');
        Route::get('eliminar/{id}', 'AdminController@documentos_eliminar')->name('admin.documentos.eliminar');
        Route::get('firmar/{token}', 'AdminController@documentos_firmar')->name('admin.documentos.firmar');
        Route::post('firmar/enviar', 'AdminController@documentos_firmar_enviar')->name('admin.documentos.firmar.enviar');
        Route::post('firmar/sign', 'AdminController@documentos_firmar_sign')->name('admin.documentos.firmar.sign');
        Route::get('descargar/{id}', 'AdminController@documentos_descargar')->name('admin.documentos.descargar');
        Route::get('politica-de-privacidad', 'AdminController@ver_politica_privacidad')->name('admin.documento.politica');
    });

    Route::group(['prefix' => 'contratos'], function () {
        Route::get('{id}/editar', 'ContratoController@editar')->name('admin.contratos.editar');
        Route::post('crear-campos', 'ContratoController@crear_campos')->name('admin.contratos.crear_campos');
        Route::post('guardar-campos', 'ContratoController@guardar_campos')->name('admin.contratos.guardar_campos');
        Route::get('borrador/{id}/{doc_id}', 'ContratoController@borrador')->name('admin.contratos.borrador');
        Route::post('enviar', 'ContratoController@enviar')->name('admin.contratos.enviar');
    });

    Route::group(['prefix' => 'comerciales'], function () {
        Route::get('/', 'AdminController@comerciales')->name('admin.comerciales');
        Route::get('crear', 'AdminController@comerciales_crear')->name('admin.comerciales.crear');
        Route::post('crear', 'AdminController@comerciales_crear_enviar')->name('admin.comerciales.crear.enviar');
        Route::post('editar', 'AdminController@comerciales_editar_enviar')->name('admin.comerciales.editar.enviar');
        Route::get('editar/{id}', 'AdminController@comerciales_editar')->name('admin.comerciales.editar');
        Route::post('editar/{id}', 'AdminController@comerciales_editar_enviar')->name('admin.comerciales.editar.enviar');
    });

    Route::group(['prefix' => 'planos'], function () {
        Route::get('/', 'AdminController@planos')->name('admin.planos');
        Route::get('ver/{plano_id}', 'AdminController@planos_ver')->name('admin.planos.ver');
        Route::post('crear', 'AdminController@planos_crear')->name('admin.planos.crear');
        Route::get('borrar/{id}', 'AdminController@planos_borrar')->name('admin.planos.borrar');
        Route::group(['prefix' => '{id}'], function () {
            Route::group(['prefix' => 'mesas'], function () {
                Route::get('/', 'AdminController@mesas')->name('admin.mesas');
                Route::post('crear', 'AdminController@mesas_crear')->name('admin.mesas.crear');
                Route::post('guardar', 'AdminController@mesas_guardar')->name('admin.mesas.guardar');
                Route::post('generar', 'AdminController@mesas_generar')->name('admin.mesas.generar');
            });
        });
    });

    Route::group(['prefix' => 'oferta-gastronomica'], function () {
        Route::get('/', 'AdminController@oferta_gastronomica')->name('admin.oferta_gastronomica');
        Route::get('/add', 'AdminController@add_oferta_gastronomica_view')->name('admin.add_oferta_gastronomica');
        Route::post('/add', 'AdminController@add_oferta_gastronomica');
        Route::group(['prefix' => '{id}'], function () {
            Route::get('/', 'AdminController@edit_oferta_gastronomica_view')->name('admin.oferta_gastronomica.edit');
            Route::post('/', 'AdminController@edit_oferta_gastronomica');
            Route::get('ocultar-mostrar', 'AdminController@ocultar_mostrar_oferta_gastronomica')->name('admin.oferta_gastronomica.ocultar_mostrar');
        });

        Route::get('/add/grupo', 'AdminController@add_grupo_oferta')->name('admin.add_grupo_oferta');
        Route::post('/add/grupo', 'AdminController@crear_grupo_oferta')->name('admin.crear_grupo_oferta');
        Route::get('/add/grupo/{id}', 'AdminController@editar_grupo_oferta')->name('admin.editar_grupo_oferta');
        Route::post('/add/grupo/{id}', 'AdminController@modificar_grupo_oferta')->name('admin.modificar_grupo_oferta');

        Route::get('/get-subcategorias/{id_categoria_padre}', 'AdminController@get_subcategs')->name('admin.oferta_gastronomica.get_subcategs');
    });

    Route::group(['prefix' => 'emails'], function () {
        Route::get('/', 'AdminController@emails')->name('admin.emails');
        Route::get('{id}/editar', 'AdminController@emails_edit')->name('admin.emails.edit');
        Route::post('edit', 'AdminController@emails_edit_enviar')->name('admin.emails.edit.enviar');
        Route::get('{id}/preview', 'AdminController@emails_preview')->name('admin.emails.preview');
    });
});

/*** COMERCIAL ***/
Route::group(['prefix' => 'com', 'middleware' => 'role'], function () {
    Route::get('/', 'ComercialController@resumen')->name('com');
    Route::get('novedades', 'ComercialController@novedades')->name('com.novedades');
    Route::group(['prefix' => 'bodas'], function () {
        Route::get('/', 'ComercialController@bodas')->name('com.bodas');
    });
    //Route::post('subir', 'ComercialController@documento')->name('com.documento');
});

/*** NOVIOS ***/
Route::group(['prefix' => 'mi-boda', 'middleware' => 'role'], function () {
    Route::get('firmar/{token}', 'UserController@documentos_firmar')->name('user.documentos.firmar');
    Route::post('firmar/sign', 'UserController@documentos_firmar_sign')->name('user.documentos.firmar.sign');
    Route::get('/', 'UserController@mi_boda')->name('user.boda');
    Route::get('datos', 'UserController@datos')->name('user.datos');
    Route::get('mensajes', 'UserController@mensajes')->name('user.mensajes');
    //Route::get('download/{file_name}', 'UserController@download');
    Route::get('pagos', 'UserController@pagos')->name('user.pagos');
    Route::get('facturacion', 'UserController@facturacion')->name('user.facturacion');
    Route::get('documentos', 'UserController@documentos')->name('user.documentos');
    Route::get('oferta', 'UserController@oferta')->name('user.oferta');
    Route::group(['prefix' => 'mesas'], function () {
        Route::get('/', 'UserController@mesas')->name('user.mesas');
        Route::get('/planner/{token}/{mesa_id?}', 'UserController@mesas_datos')->name('user.mesas.datos');
    });

    Route::post('update/ingreso', 'UserController@update_fecha_ingreso')->name('user.boda.update.ingreso');

    Route::group(['prefix' => 'invitados'], function () {
        Route::get('/', 'UserController@invitados')->name('user.invitados');
        Route::post('crear', 'UserController@invitados_crear')->name('user.invitados.crear');
        Route::post('mesa/update', 'UserController@invitados_mesa_update')->name('user.invitados.mesa.update');
        Route::post('estado/update', 'UserController@invitados_estado_update')->name('user.invitados.estado.update');
        Route::post('alergenos/editar', 'UserController@invitados_alergenos_editar')->name('user.invitados.alergenos.editar');
    });

    Route::group(['prefix' => 'gastronomia'], function () {
        Route::get('/', 'UserController@gastronomia')->name('user.gastronomia');
    });
});

Route::get('/mi-boda/completar/{token?}', 'AdminController@bodas_completar')->name('admin.bodas.completar');
Route::post('/mi-boda/completar/enviar', 'AdminController@bodas_completar_enviar')->name('admin.bodas.completar.enviar');

/*** INVITADOS ***/
Route::group(['prefix' => 'guests'], function () {
    Route::get('/', 'GuestController@guests');
    Route::get('confirmar/{token}', 'GuestController@guests_confirmar')->name('guests.confirmar');
    Route::post('confirmar/{token}', 'GuestController@guests_confirmar_enviar')->name('guests.confirmar.enviar');
    Route::get('delete/{token}/{id}', 'GuestController@guests_delete')->name('guests.delete');
});

/*** API ***/
Route::group(['prefix' => 'api'], function () {
    Route::get('check', function () {
        return true;
    });

    /*** AUTH ***/
    Route::post('login', 'ApiController@auth_login');
    Route::get('autologin/{id}', 'ApiController@auth_autologin')->name('api.autologin');
    Route::post('update/token', 'ApiController@user_update_token')->name('api.update');
    Route::get('update/token/{email}/{token}', 'ApiController@user_update_token_get')->name('api.update.get');

    /*** USERS ***/
    Route::group(['prefix' => 'user'], function () {
        Route::get('bodas', 'ApiController@user_bodas')->name('api.user.bodas');
    });

    /*** BODAS ***/
    Route::group(['prefix' => 'boda'], function () {
        Route::get('/', function () {
            return 'asd';
        });
    });

    /*** Categorias ***/
    Route::group(['prefix' => 'categorias'], function () {
        Route::get('/', 'ApiController@categorias');
    });
});

Route::get('pdf/{url}', function ($url) {
    return $url;
    return '<iframe style="width:100%;height:100vh" src="http://docs.google.com/gview?embedded=true&url=' . $url . '"></iframe>';
})->name('pdf');

/*** OTRAS FUNCIONES ***/
Route::group(['prefix' => 'bodas'], function () {
    Route::get('completar-oferta-gastronomica/{token}/{id}', 'AdminController@bodas_completar_oferta_gastronomica')->name('admin.bodas.completar_oferta_gastronomica');
    Route::post('completar-oferta-gastronomica/{token}/{id}', 'AdminController@bodas_completar_oferta_gastronomica_enviar')->name('admin.bodas.completar_oferta_gastronomica.enviar');
});

Route::get('qwe', function () {
    return App\Models\User::limit(4)->get();
});

require __DIR__ . '/auth.php';

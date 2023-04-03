<?php

namespace App\Http\Controllers;
use App\Models\Boda;
use App\Models\DatosFacturacion;
use App\Models\Justificante;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class FacturacionController extends Controller
{
    public function index() {
        return redirect()->route('facturacion.data');
    }

    public function index_datafacturacion() {
        return view('facturacion.data');
    }

    public function create_datafacturacion() {
        return view('facturacion.data_create');
    }

    public function save_datafacturacion(Request $request) {
        if($request->has('datos_id')){
            $datos = DatosFacturacion::find($request->datos_id);
            $datos->fill($request->except(['_token']));
        } else {
            $datos = new DatosFacturacion($request->except(['_token']));
        }
        $datos->save();
        if(user()->rol == 'user') {
            return redirect()->route('facturacion.data', ['id' => $datos->id])->withSuccess('Datos de facturación guardados correctamente');
        } else {
            return redirect()->route('admin.bodas.ver', ['id' => $request->boda_id])->withSuccess('Datos de facturación guardados correctamente');
        }
    }

    public function ver_datafacturacion(Request $request) {
        $datos = DatosFacturacion::findOrFail($request->id);
        return view('facturacion.data_ver', compact('datos'));
    }

    public function delete_datafacturacion(Request $request) {
        $datos = DatosFacturacion::find($request->datos_id);
        if($datos != null) {
            $datos->delete();
            if(user()->rol == 'user') {
                return redirect()->route('facturacion.data', ['id' => $request->datos_id])->withSuccess('Datos de facturación eliminados correctamente');
            } else {
                return redirect()->route('admin.bodas.ver', ['id' => $request->boda_id])->withSuccess('Datos de facturación eliminados correctamente');
            }
        }
    }

}

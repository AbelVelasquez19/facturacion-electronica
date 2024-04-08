<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::where('user_id',auth()->id())->get();
        return response()->json($company,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'razon_social'=>'required|string',
            'ruc'=>[
                'required',
                'string',
                'regex:/^(10|20)\d{9}$/',
                new \App\Rules\UniqueRucRule()
            ],
            'direccion'=>'required|string',
            'logo_path'=>'nullable|image',
            'sol_user'=>'required|string',
            'sol_pass'=>'required|string',
            //extencion
            'cert_path'=>'required|file|mimes:pem,txt',
            'client_id'=>'nullable|string',
            'client_secret'=>'nullable|string',
            'production'=>'nullable|boolean',
        ]);
        if($request->hasFile('logo_path')){
            $data["logo_path"] = $request->file('logo_path')->store('logos');    
        }
        $data["cert_path"] = $request->file('cert_path')->store('certs');
        $data["user_id"] = JWTAuth::user()->id;

        $company = Company::create($data);

        return response()->json([
            'message'=>'Empresa creada correctamente',
            'company'=>$company,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($company)
    {
        $company=Company::where('ruc',$company)->where('user_id',JWTAuth::user()->id)->firstOrFail();
        return response()->json([
            'comany'=>$company
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $company)
    {
        $company = Company::where('ruc',$company)->where('user_id',JWTAuth::user()->id)->firstOrFail();

        $data = $request->validate([
            'razon_social'=>'required|string',
            'ruc'=>[
                'nullable',
                'string',
                'regex:/^(10|20)\d{9}$/',
                new \App\Rules\UniqueRucRule($company->id)
            ],
            'direccion'=>'nullable|string|min:5',
            'logo_path'=>'nullable|image',
            'sol_user'=>'nullable|string',
            'sol_pass'=>'nullable|string',
            //extencion
            'cert_path'=>'nullable|file|mimes:pem,txt',
            'client_id'=>'nullable|string',
            'client_secret'=>'nullable|string',
            'production'=>'nullable|boolean',
        ]);
        if($request->hasFile('logo_path')){
            $data["logo_path"] = $request->file('logo_path')->store('logos');    
        }
        if($request->hasFile('cert_path')){
            $data["cert_path"] = $request->file('cert_path')->store('certs');
        }

        $company->update($data);

        return response()->json([
            'message'=>'Empresa actualizada correctamente',
            'company'=>$company,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($company)
    {
       $company = Company::where('ruc',$company)->where('user_id',JWTAuth::user()->id)->firstOrFail();
       $company->delete();
       return response()->json([
        'message'=>'Empresa eliminado correctamente'
    ]);
    }
}

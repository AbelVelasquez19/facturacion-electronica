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
        return JWTAuth::user();;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'razon_social'=>'required|string',
            'ruc'=>'required|string',
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
            $data["cert_path"] = $request->file('logo_path')->store('logos');    
        }
        $data["logo_path"] = $request->file('cert_path')->store('certs');
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
    public function show(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
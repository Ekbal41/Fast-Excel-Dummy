<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


use Maatwebsite\Excel\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function welcome (){
        $users = User::all();
        return view('welcome',compact('users'));
    }
    public function exportusers(){
        
       
        return (new FastExcel(User::all()))->download('file.xlsx');
    
       
    }
    public function import (Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);
        $filePath = $request->file('file')->path();
        $newFilePath =  $filePath . '.' . $request->file('file')->getClientOriginalExtension();
        move_uploaded_file($filePath, $newFilePath);

        $users = (new FastExcel)->import($newFilePath, function ($line) {
            
            return User::create([
                'name' => $line['name'],
                'email' => $line['email'],
                'email_verified_at' => $line['email_verified_at'],
                'created_at' => $line['created_at'],
                'updated_at' => $line['updated_at'],
                'password' => 'dummy',
            ]);

        });
        foreach ($users as $user) {
            $user->save();
        }
        return redirect()->route('welcome');

    }
    public function import_data_with_pass (Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);
        $filePath = $request->file('file')->path();
        $newFilePath =  $filePath . '.' . $request->file('file')->getClientOriginalExtension();
        move_uploaded_file($filePath, $newFilePath);

        (new FastExcel)->import($newFilePath, function ($line) {
            $users = User::all();
            
            foreach ($users as $user) {
                
                    $user->password = $line['password'];
                    $user->save();
                    
                
            }

        });
        
       
        return redirect()->route('welcome');

    }

    public function export_with_password(){
        $data = User::all()->map(function ($user) {
            
            return [
                //'name' => $user->name,
//'email' => $user->email,
//'email_verified_at' => $verifed,
//'created_at' => $created,
               // 'updated_at' => $updated,
               
                'password' => $user->password,
            ];
        });

       return (new FastExcel($data))->download('file.xlsx');
       

        

       
    }
}
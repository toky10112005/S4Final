<?php
namespace App\Controllers;

use App\Models\UsersModel;

class UsersController extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $usersmodel=new UsersModel();
        $user = $usersmodel->getUserByEmail($email);

        if(empty($user)){
            return redirect()->back()->with('error', 'Diso na tsisy ilay email');
        }
        else{
        //    if(password_verify($password,$user['password'])){
        if($password==$user['password']){

                session()->set('user',[
                    'id'=>$user['id'],
                    'username'=>$user['username'],
                    'role'=>$user['role']
                ]);

                if($user['role']=='admin'){
                    return redirect()->to('/admindashboard');
                }
                else{
                    return redirect()->to('/userdashboard');
                }
           }
           else{
                return redirect()->back()->with('error', 'Diso ilay password');
           }
        }  
    }

    public function RedirectInscription()
    {
        return view('/inscription');
    }

    public function inscription()
    {
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $usersmodel=new UsersModel();
        $user = $usersmodel->getUserByEmail($email);

        if(!empty($user)){
            return redirect()->back()->with('error', 'Efa misy ilay email');
        }
        else{
            $data=[
                'username'=>$username,
                'email'=>$email,
                'password'=>$password,
                'role'=>'user'
            ];
            $usersmodel->insert($data);
            // return redirect()->to('/login')->with('success', 'Voasoratra anarana soa aman-tsara');
            return view('login', ['success' => 'Voasoratra anarana soa aman-tsara']);
        }
    }

    public function logout()
    {
        session()->remove('user');
        return redirect()->to('/login');
    }

    public function admindashboard()
    {
        return view('admin/dashboard');
    }
     public function userdashboard()
    {
        return view('user/p1');
    }
}
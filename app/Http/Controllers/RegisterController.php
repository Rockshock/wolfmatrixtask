<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(){
        return view('auth.register');
    }

    public function register(RegisterRequest $request){
        $this->userRepository->create($request->validated());

        return redirect()->route('login')->with('success', 'Account successfully created. You can now log in.');
    }
}
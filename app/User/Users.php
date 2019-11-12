<?php
namespace App\User;
use App\User as UserRespositry;


class Users
{

	private  $users;


	function __construct(){
		$this->users = UserRespositry::all();
	}



	public  function all(){
		return $this->users;;
	}

	public  function vendors(){
		return $this->users->filter(function ($user) { return  $user->is_vendor; });
	}



	public  function clients(){
		return $this->users->filter(function ($user) { return  $user->is_client; });
	}


	public  function managers(){
		return $this->users->filter(function ($user) { return  $user->is_manager; });
	}

	public  function suppliers(){
		return $this->users->filter(function ($user) { return  $user->is_vendor; });
	}



}

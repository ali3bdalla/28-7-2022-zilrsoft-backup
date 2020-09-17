<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'email_address' => 'required|email|unique:users,email_address',
            'password' => 'required|string|min:7|confirmed',
            'name' => 'required|string|min:2',
            'phone_number' => 'required|string|min:6|max:15'
        ];
    }


    public function save()
    {
//        return [];

        $response = null;
        DB::beginTransaction();
        try {
            $user = new User;
            $user->organization_id = 1;
            $user->creator_id = 1;
            $user->email_address = $this->input("email_address");
            $user->password = bcrypt($this->input("password"));
            $user->phone_number = $this->input("phone_number");
            $user->name = $this->input("name");
            $user->user_slug = 'web';
            $user->is_client = true;
            $user->user_type = 'individual';
            $user->user_title = 'mr';
            $user->save();




            DB::commit();
            $response = response([
                'user' => $user->fresh(),
                'token' => $user->createToken("clientToken")->accessToken
            ]);
        } catch (\Exception $exception) {

            DB::rollBack();
            $response = response(['message' => $exception->getMessage()], 500);
        }


            return $response;


        }
}

<?php

    namespace App\Http\Requests\Accounting\Manager;

    use App\Models\Account;
    use App\Rules\ExistsRule;
    use Exception;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Support\Facades\DB;

    class CreateManagerRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize()
        {
            return $this->user()->can('manage managers');
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
                'email'           => 'required|email:rfc,dns,filter|organization_unique:App\Models\Manager,email',
                'password'        => 'required|string|min:7|confirmed',
                'name'            => 'required|string|min:2',
                'name_ar'         => 'required|string|min:2',
                'phone_number'         => 'required|string',
                'branch_id'       => 'required|integer|organization_exists:App\Models\Branch,id',
                'department_id'   => 'required|integer|organization_exists:App\Models\Department,id',
                'delivery_man_id' => 'nullable|integer|exists:delivery_men,id',

                'permissions'   => 'array|nullable',
                'permissions.*' => 'string|exists:permissions,name',
                'gateways.*.id' => ['integer', new ExistsRule(Account::class)],
            ];
        }

        public function save()
        {
            $user = null;
            DB::beginTransaction();
            try {
                $current = $this->user();

                $data['is_manager']      = true;
                $data['is_vendor']       = false;
                $data['is_supplier']     = false;
                $data['is_client']       = false;
                $data['is_supervisor']   = false;
                $data['can_make_credit'] = false;
                $data['user_type']       = 'individual';
                $data['phone_number']    = $this->phone_number;
                $data['name_ar']         = $this->ar_name;
                $data['name']            = $this->name;
                $data['user_title']      = 'mr';
                $data['creator_id']      = $current->id;
                $user                    = $current->organization->users()->create($data);

                $manager = $user->manager()->create([
                    'password'        => bcrypt($this->password),
                    'email'           => $this->email,
                    'phone_number'           => $this->phone_number,
                    'delivery_man_id' => $this->delivery_man_id,
                    'name_ar'         => $this->name_ar,
                    'name'            => $this->name,
                    'organization_id' => $current->organization_id,
                    'branch_id'       => $this->branch_id,
                    'department_id'   => $this->department_id,
                ]);

                if (! empty($this->gateways)) {
                    if (! empty($this->gateways)) {
                        foreach ($this->gateways as $gateway) {
                            $manager->gateways()->attach(
                                $gateway['id'],
                                [
                                    'organization_id' => $current->organization_id,
                                ]
                            );
                        }
                    }
                }
                if (! empty($this->permissions)) {
                    $manager->givePermissionTo($this->permissions);
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }

            return $user;
        }
    }

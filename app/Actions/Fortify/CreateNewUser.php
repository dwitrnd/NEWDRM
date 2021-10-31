<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'organization_id' => ['required','exists:organizations,id'],
            'employee_id' => ['required','exists:employees,id','unique:users'],
            'role_id' => ['required','exists:roles,id'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class),
            ],
            // 'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'username' => $input['username'],
            'role_id' => $input['role_id'],
            'employee_id' => $input['employee_id'],
            'organization_id' => $input['organization_id'],            
            'password' => Hash::make('Deerwa1k@DRM'),
            // 'password' => Hash::make($input['password']),
        ]);
    }
}
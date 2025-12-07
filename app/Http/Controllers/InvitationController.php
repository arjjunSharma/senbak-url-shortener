<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class InvitationController extends Controller
{
    public function create(Request $request)
    {
        $this->authorize('invite-user');

        return view('invitations.create', [
            'companies' => Company::all(),
            'roles' => [
                User::ROLE_SUPER_ADMIN,
                User::ROLE_ADMIN,
                User::ROLE_MEMBER,
                User::ROLE_SALES,
                User::ROLE_MANAGER,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('invite-user');

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'role'        => 'required|in:SuperAdmin,Admin,Member,Sales,Manager',
            'company_id'  => 'nullable|exists:companies,id',
            'new_company' => 'nullable|string|max:255',
            'password'    => 'required|string|min:8',
        ]);

        $acting = $request->user();

        $companyId = $data['company_id'] ?? null;

        if (!empty($data['new_company'])) {
            $newCompany = Company::create(['name' => $data['new_company']]);
            $companyId = $newCompany->id;

            // SuperAdmin can’t invite an Admin in a NEW company
            if ($acting->isSuperAdmin() && $data['role'] === User::ROLE_ADMIN) {
                return back()->withErrors([
                    'role' => 'SuperAdmin cannot invite an Admin into a new company.',
                ])->withInput();
            }
        }

        // Admin can’t invite another Admin or Member in their own company
        if ($acting->isAdmin()
            && $companyId === $acting->company_id
            && in_array($data['role'], [User::ROLE_ADMIN, User::ROLE_MEMBER], true)
        ) {
            return back()->withErrors([
                'role' => 'Admin cannot invite another Admin or Member into their own company.',
            ])->withInput();
        }

        User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => $data['role'],
            'company_id' => $companyId,
        ]);

        return redirect()->route('invitations.create')
            ->with('status', 'User invited successfully.');
    }
}

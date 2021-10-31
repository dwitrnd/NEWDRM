<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ManagerRequest;
use App\Models\Manager;
use App\Models\Employee;

use Illuminate\Support\Facades\DB;


class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $managers = Manager::select('id', 'employee_id','is_active')
                            ->with('employee:id,first_name,last_name')
                            ->orderBy('id')
                            ->paginate(10);
        return view('admin.manager.index')->with(compact('managers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $managers = Manager::select('id','employee_id','is_active')->get();
        $employees = Employee::select('id','first_name', 'last_name')->get();
        return view('admin.manager.create')->with(compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManagerRequest $request)
    {
        Manager::create($request->validated());
        return redirect('/manager');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manager = Manager::select('id', 'employee_id', 'is_active')->findOrFail($id);
        $employees = Employee::select('id','first_name', 'last_name')->get();
        return view('admin.manager.edit')->with(compact('manager','employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ManagerRequest $request, $id)
    {
        $manager = Manager::findOrFail($id);
        
        //get validated input and merge input fields
        $input = $request->validated();
        $input['version'] = DB::raw('version+1');

        $manager->update($input);
        return redirect('/manager');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $manager = Manager::findOrFail($id);
            $manager->delete();
            return redirect('/manager');
        }
        catch(\Illuminate\Database\QueryException $e){
            if($e->getCode() == "23000"){
                return redirect()->back();
            }
        }
    }
}
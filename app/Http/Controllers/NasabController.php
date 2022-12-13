<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use App\Models\Person;
use Illuminate\Http\Request;

class NasabController extends Controller
{
    public function createChild()
    {
        // dapatkan person id dari yang login
        // $id = Auth::user()->id;

        // ini sebagai contoh statis
        $id = 1;

        $person = Person::with(['wifes'])->find($id);
        $couples = $person->couples;

        $data = [
            'person' => $person,
            'couples' => $couples,
        ];
        return view('child.create', $data);
    }

    public function storeChild(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'parent_id' => 'required',
        ]);

        //ambil pasangan di model Couple
        $couple = Couple::find($request->parent_id);

        // megisi mother id dan father id
        $validated['mother_id'] = $couple->wife_id;
        $validated['father_id'] = $couple->husband_id;

        Person::insert($validated);

        return redirect('/');
    }
}

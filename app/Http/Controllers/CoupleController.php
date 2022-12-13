<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use App\Models\Person;
use Illuminate\Http\Request;

class CoupleController extends Controller
{
    public function create()
    {
        return view('couple.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'date_wedding' => 'required',
        ]);

        // dapatkan person id dari yang login
        // $id = Auth::user()->id;

        // ini sebagai contoh statis
        $id = 1;
        $person = Person::find($id);

        //isi jenis kelamin
        $validated['gender'] = 'P';


        $istri = Person::create($validated);

        $Couple = Couple::create([
            'wife_id' => $istri->id,
            'husband_id' => $person->id,
            'date_wedding' => $request->wedding,
        ]);

        return redirect('/');
    }
}

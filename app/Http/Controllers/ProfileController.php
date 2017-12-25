<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\User $profileUser
     * @return \Illuminate\Http\Response
     */
    public function show($profileUser)
    {
        $activities_by_date = $profileUser->activities()->with('subject')->orderBy('id', 'desc')->get()->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });

        return view('profiles.show', [
            'profileUser' => $profileUser,
            'threads' => $profileUser->threads()->orderBy('id', 'desc')->paginate(1),
            'activities_by_date' => $activities_by_date,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

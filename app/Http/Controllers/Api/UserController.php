<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return User::all();
    }

 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
     $createPoste = $request->validate([
        'name'                         => 'required|string|unique:users',
        'post_content'                 => 'string',
        'header_image'                 => 'required|string',
        'footer_image'                 => 'required|string',
        'bgcolor'                      => 'nullable|string',
        ]);


        //convert into header_image 
        $name = time().'.' . explode('/', explode(':', substr($request->header_image, 0, strpos($request->header_image, ';')))[1])[1];
        \Image::make($request->header_image)->save(public_path('header_images/').$name);
        $request->merge(['header_image' => $name]);
        $header_image  =  $request->root().'/header_images/'.$name;

        //convert into header_image 
        $name = time().'.' . explode('/', explode(':', substr($request->footer_image, 0, strpos($request->footer_image, ';')))[1])[1];
        \Image::make($request->footer_image)->save(public_path('footer_images/').$name);
        $request->merge(['footer_image' => $name]);
        $footer_image  =  $request->root().'/footer_images/'.$name;

        $createPoste = User::create([
            'name'                         => $request->name,
            'post_content'                 => $request->post_content,
            'bgcolor'                      => $request->bgcolor,
            'slug'                         => Str::slug($request->name, '-'),
            
            'header_image'                 => $header_image,
            'footer_image'                 => $footer_image,
        ]);


    return $createPoste;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // $this->authorize('isAdmin');

        // $user = User::where('id',$id)->with('profile')->get();
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $createPoste = $request->validate([
            'name'                         => 'required|string|unique:users',
            'post_content'                 => 'string',
            'bgcolor'                      => 'nullable|string',
            ]);
    
    
            if ($request->has('header_image') && $request->header_image != "") {
                // convert into header_image 
                $name = time().'.' . explode('/', explode(':', substr($request->header_image, 0, strpos($request->header_image, ';')))[1])[1];
                \Image::make($request->header_image)->save(public_path('header_images/').$name);
                $request->merge(['header_image' => $name]);
                $header_image  =  $request->root().'/header_images/'.$name;

                 $user->header_image   = $request->header_image;
            }

            if ($request->has('footer_image') && $request->footer_image != "") {
                // convert into header_image 
                $name = time().'.' . explode('/', explode(':', substr($request->footer_image, 0, strpos($request->footer_image, ';')))[1])[1];
                \Image::make($request->footer_image)->save(public_path('footer_images/').$name);
                $request->merge(['footer_image' => $name]);
                $footer_image  =  $request->root().'/footer_images/'.$name; 


                 $user->footer_image   = $request->footer_image;
            }
    
            $user->name           = $request->name;
            $user->slug           = Str::slug($request->name, '-');
            $user->post_content   = $request->post_content;
            $user->bgcolor        = $request->bgcolor;


            $user->save();
    
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Utils\CustomValidator;
use Exception;
use PDOException;

class PlaceController extends Controller
{
    public function index()
    {
        return response()->json(Place::all());
    }

    public function show($id)
    {
        $place = Place::find($id);

        if (!$place) {
            return response()->json(['error' => 'Sorry, This Place not found'], 404);
        }

        return response()->json($place);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:places',
            'city' => 'required|string',
            'state' => 'required|string|max:2',
        ]);

        $cleanedName = CustomValidator::removeNumbers($request->input('name'));
        $cleanedSlug = CustomValidator::generateSlug($request->input('slug'));
        $cleaneCity  = CustomValidator::removeNumbers($request->input('city'));
        $cleanState = CustomValidator::isValidState($request->input('state'));

        if(!$cleanState){
            return response()->json(['error' => 'State must have two characters and be from some state in Brazil'], 422);
        }else{
            $cleanState = strtoupper($request->input('state'));
        }

        $data = [
            'name' => $cleanedName,
            'slug' => $cleanedSlug,
            'city' => $cleaneCity,
            'state' => $cleanState
        ];

         DB::beginTransaction();

        try {
        
            $place = Place::create($data);

            DB::commit();

            return response()->json(['success' => 'Place created successfully'], 201);
        } catch (PDOException $e) {
            DB::rollBack();
            
            $errorCode = $e->errorInfo[1];
          
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate entry for the slug'], 422);
            }

            return response()->json(['error' => 'Failed to create place'], 500);
        }
    }

    public function update(Request $request, $id)
    {
            
            $place = Place::find($id);

           
            if (!$place) {
                return response()->json(['error' => 'Place not found'], 404);
            }
     
            $allowedFields = ['name', 'slug', 'city', 'state'];
            $fieldsToUpdate = $request->only($allowedFields);

            foreach ($fieldsToUpdate as $field => $value) {
                switch ($field) {
                    case 'name':
                        $fieldsToUpdate[$field] = CustomValidator::removeNumbers($value);
                        break;
                    case 'slug':
                        // Verificar unicidade antes de atribuir o novo valor
                        $newSlug = CustomValidator::generateSlug($value);
                        if ($newSlug !== $place->slug && Place::where('slug', $newSlug)->exists()) {
                            return response()->json(['error' => 'Sorry, Slug must be unique'], 422);
                        }
                        $fieldsToUpdate[$field] = $newSlug;
                        break;
                    case 'city':
                        $fieldsToUpdate[$field] = CustomValidator::removeNumbers($value);
                        break;
                    case 'state':
                        $state = CustomValidator::isValidState($value);
                        if(!$state){
                            return response()->json(['error' => 'State must have two characters and be from some state in Brazil'], 422);
                        }else{
                            $fieldsToUpdate[$field] = strtoupper($value);
                            break;
                        }
                }
            }
        // Verificar se há campos não permitidos na requisição
        $disallowedFields = array_diff(array_keys($request->all()), $allowedFields);
        if (!empty($disallowedFields)) {
            return response()->json(['error' => 'Fields not allowed for update: ' . implode(', ', $disallowedFields)], 422);
        }   

        DB::beginTransaction();
        try {
        
            $place->update($fieldsToUpdate);

            DB::commit();

            return response()->json(['success' => 'Place update successfully'], 201);
        } catch (Exception $e) {
            DB::rollBack();
        
            return response()->json(['error' => 'Failed to update place'], 500);
        }

    }

    public function destroy($id)
    {
        $place = Place::find($id);

        if (!$place) {
            return response()->json(['error' => 'Sorry, This Place not found'], 404);
        }

        DB::beginTransaction();

        try {
        
            $place->delete();

            DB::commit();

            return response()->json(['success' => 'Place deleted successfully'], 201);
        } catch (PDOException $e) {
            DB::rollBack();
          
            return response()->json(['error' => 'Failed to delete place'], 500);
        }
    
    }

}

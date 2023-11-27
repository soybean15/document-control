<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasPDF;
use App\Http\Traits\HasQR;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PDF;
class DocumentController extends Controller
{
    //

    use HasPDF,HasQR;

    public function index()
    {

        return Inertia::render('Document', [
            'create' => route('user.create'),
            'users' => UserInfo::all(),
            'documentType' => DocumentType::all() ,
            'documents'=>fn()=> Document::with(['user','userInfo','documentType'])->get()

        ]);

    }

    public function storeType(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255',

        ])->validate();

        DocumentType::create([
            'name' => $request['name']
        ]);

        return to_route('document');



    }

    public function upload(Request $request)
    {


        //return dd($request->file());


        $user = Auth::user();

        // return dd($user->id);

        if ($file = $request->file('file_path')) {




            $fileExtension = $file->getClientOriginalExtension();
            $ref_id = uniqid();
         


            $document = Document::create([
                'user_id' => $user->id,
                'user_info_id' => $request['user_info_id'],
                'ref_id' => $ref_id,
                'path' => $ref_id,
                'document_type_id' => $request['document_type_id']

            ]);

            if($fileExtension === 'docx'){
                $fileName = $ref_id . '.' . 'pdf';

                $Content = $this->converToPDF($file,$fileName);
                $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'PDF');
                $PDFWriter->save(public_path('documents/temp/'.$fileName)); 
              
            }else{
                $fileName = $ref_id . '.' . $fileExtension;
                $file->move('documents/temp/', $fileName);
            }

           

            $document->path = $fileName;
            $document->save();

            $outputFilePath = public_path("documents/files/".$fileName);
            $this->fillPDFFile("documents/temp/".$fileName, $outputFilePath);

        }


        return to_route('document');
    }


    public function showDocument(String $id)
    {

        
        $document = Document::find($id);


        return response()->file('documents/files/'.$document->path);



    }


}

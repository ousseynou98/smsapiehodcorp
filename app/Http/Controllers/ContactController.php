<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;




class ContactController extends Controller
{
    public function index($id)
    {
        $contacts = Contact::where('id_campagne', $id)->get();
        return response()->json($contacts);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contact = new Contact;
        $contact->nom = $request->nom;
        $contact->numero = $request->numero;
        $contact->id_contact = $request->idCampagne;
        $contact->save();

        return response()->json($contact);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json($contact);
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
        $contact = Contact::findOrFail($id);
        $contact->nom = $request->nom;
        $contact->numero = $request->numero;
        $contact->update();
        return response()->json($contact);
        
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response()->json(['message' => 'Contact supprimée avec succès ']);
    }


    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
            'idCampagne' => 'required',
        ]);
    
        $file = $request->file('file');
        $id_campagne = $request->input('idCampagne');
    
        // On génère un nom unique pour le fichier
        $fileName = time() . '_' . $file->getClientOriginalName();
    
        // On stocke le fichier dans le dossier "uploads"
        $path = $file->storeAs('uploads', $fileName);
    
        // On charge le fichier Excel avec la librairie PHPSpreadsheet
        $reader = IOFactory::createReaderForFile(storage_path('app/'.$path));
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(storage_path('app/'.$path));
    
        // On récupère la première feuille du fichier
        $worksheet = $spreadsheet->getActiveSheet();
    
        // On boucle sur les lignes de la feuille en ignorant la première ligne (l'en-tête)
        foreach ($worksheet->getRowIterator(2) as $row) {
            // On récupère les valeurs de la ligne
            $nom = $worksheet->getCellByColumnAndRow(1, $row->getRowIndex())->getValue();
            $numero = $worksheet->getCellByColumnAndRow(2, $row->getRowIndex())->getValue();
    
            // On supprime les espaces en début et fin de chaîne pour le nom et le numéro
            $nom = trim($nom);
            $numero = trim($numero);
    
            // On vérifie que le nom et le numéro sont non vides
            if (empty($nom) || empty($numero)) {
                continue;
            }
    
            // On vérifie que le numéro est valide (contient uniquement des chiffres, des espaces ou des signes + et -)
            if (!preg_match('/^[\d\s\+\-]+$/', $numero)) {
                continue;
            }
    
            // On vérifie que le nom et le numéro n'existent pas déjà dans la base de données pour cette campagne
            $exists = DB::table('contacts')
                ->where('nom', $nom)
                ->where('numero', $numero)
                ->where('id_campagne', $id_campagne)
                ->exists();
    
            if (!$exists) {
                // On insère le contact dans la base de données
                DB::table('contacts')->insert([
                    'nom' => $nom,
                    'numero' => $numero,
                    'id_campagne' => $id_campagne,
                ]);
            }
        }
    
        return response()->json(['message' => 'Contacts imported successfully']);
       
    }
}

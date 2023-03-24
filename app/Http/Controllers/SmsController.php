<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\SMS;

class SmsController extends Controller
{
    public function sendSMS(Request $request)
    {

        // Récupérer la liste des numéros de téléphone à partir de la campagne
        $idCampagne = $request->input('idCampagne');
        $contacts = Contact::where('id_campagne', $idCampagne)->pluck('numero')->toArray();

        // Préparer les données pour l'envoi de SMS
        $accountid = 'CURSIVE';
        $password = 'MLL6Wd6939re9qeQ';
        $message = $request->input('message');
        $sender = 'MOSQUEE_TIVAOUANE';
        $text = urlencode($message);
        $urlapi = 'https://lampush-tls.lafricamobile.com/api';

        // Envoyer les SMS
        $successCount = 0;
        $failedCount = 0;
        $failedNumbers = array();
        foreach ($contacts as $phoneNumber) {
            $full_url_called = $urlapi.'?'."accountid=$accountid&password=$password"
                ."&text=$text"
                ."&to=00221$phoneNumber"
                ."&sender=$sender";
            $result = file_get_contents($full_url_called);
            // Vérifier si l'envoi a réussi
            if (preg_match('/^[0-9]+$/', $result)) {
                // L'envoi a réussi, car $result contient un ID de message valide
                $successCount++;
            } else {
                // L'envoi a échoué
                $failedCount++;
                array_push($failedNumbers, $phoneNumber);
            }
        }

        // Enregistrer les messages envoyés dans la table "SMS"
        $sms = new SMS;
        $sms->message = $message;
        $sms->numeros = implode(', ', $contacts);
        $sms->numeros_envoyes = $successCount;
        $sms->campagne = $idCampagne;
        $sms->numeros_echoues = $failedCount;
        if ($failedCount > 0) {
            $sms->numeros_echoues_details = implode(', ', $failedNumbers);
        }
        $sms->save();

        // Retourner la réponse HTTP
        if ($failedCount > 0) {
            return response([
                'message' => 'Envoi de message terminé avec '.$successCount.' numéros envoyés et '.$failedCount.' numéros échoués.',
                'numeros_echoues_details' => $failedNumbers,
            ], 500);
        } else {
            return response([
                'message' => 'Envoi de message terminé avec succès.',
            ], 200);
        }
    }

}

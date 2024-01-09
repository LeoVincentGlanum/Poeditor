<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
class PoediteurController extends Controller
{
    public function transform(Request  $request){


        $sourceLang = $request->input('sourceLang');
        $targetLang = $request->input('targetLang');

        $poFile = $request->file('po');
        $fileNamePo = time() . '.' . $poFile->getClientOriginalExtension();
        $poFile->storeAs('po', $fileNamePo, 'public');



        $cvsFile = $request->file('csv');
        $fileNameCsv = time() . '.' . $cvsFile->getClientOriginalExtension();
        $cvsFile->storeAs('csv', $fileNameCsv, 'public');

        $csv = Reader::createFromPath('storage/csv/'.$fileNameCsv, 'r');
        $csv->setHeaderOffset(0); // Si la première ligne contient les en-têtes de colonne
        $csv->setDelimiter(';');


        $arrayLang = [];
        // Parcourir les lignes du fichier CSV
        foreach ($csv as $row) {
            // Accéder aux données de chaque ligne par le nom de la colonne
            $column1 = utf8_encode($row[$sourceLang]);
            $column2 = utf8_encode($row[$targetLang]);

            $arrayLang[$column1] = $column2;


           // dump($column1,$column2);
            // Effectuer des opérations avec les données, par exemple, les enregistrer dans la base de données
            // Exemple d'enregistrement dans la base de données (à adapter à votre modèle)
            /*
            Model::create([
                'column1' => $column1,
                'column2' => $column2,
            ]);
            */
        }


//       dd(file_get_contents('storage/po/'.$fileNamePo));

        $poContent = File::get('storage/po/'.$fileNamePo);

        // Diviser le contenu du fichier .po en entrées de traduction
        $translationEntries = preg_split('/\n\s*\n/', $poContent);
        $cpt = 0;
        $allFile = "";
        // Parcourir chaque entrée de traduction
        foreach ($translationEntries as $entry) {
            $cpt++;
            if ($cpt === 1 ){
                $allFile = $entry;
                continue;
            }
            // Vous pouvez analyser chaque entrée de traduction ici
            // Pour obtenir les traductions dans votre application, vous pouvez extraire les chaînes d'origine (msgid) et les traductions (msgstr) de chaque entrée.
            $msgid = $this->getPoField($entry, 'msgid');
            $msgstr = $this->getPoField($entry, 'msgstr');
            // Faites quelque chose avec $msgid et $msgstr, comme les enregistrer dans une base de données ou les utiliser pour les traductions dans votre application.
           // dump($msgid,key_exists($msgid,$arrayLang));

            if (key_exists($msgid,$arrayLang)){
                $entry = str_replace('msgstr ""','msgstr "'.Arr::get($arrayLang,$msgid).'"',$entry);
            } else{
                $allFile = $allFile . "\n\n". $entry;
                continue;
            }
            $allFile = $allFile . "\n\n". $entry;
        }



        $filePath = storage_path('app/generated.po');

        // Le contenu que vous souhaitez écrire dans le fichier
        $content = $allFile;

        // Écrivez le contenu dans le fichier
        File::put($filePath, $content);

        $content = File::get($filePath);
        $response = response()->download($filePath, 'generated.po');
        return $response;

    }
    private function getPoField($entry, $fieldName)
    {
        $pattern = "/{$fieldName} \"(.*?)\"/";
        preg_match($pattern, $entry, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }

    public function swap(Request $request){
        $poFile = $request->file('po');
        $fileNamePo = time() . '.' . $poFile->getClientOriginalExtension();
        $poFile->storeAs('po', $fileNamePo, 'public');

        $poContent = File::get('storage/po/'.$fileNamePo);

        $translationEntries = preg_split('/\n\s*\n/', $poContent);
        $cpt = 0;
        $allFile = "";
        foreach ($translationEntries as $entry) {
            $cpt++;
            if ($cpt === 1 ){
                $allFile = $entry;
                continue;
            }

            $msgid = $this->getPoField($entry, 'msgid');
            $msgstr = $this->getPoField($entry, 'msgstr');

            if ($msgstr === ""){
                continue;
            }
            $entry = preg_replace('/msgstr "(.*?)"/', 'msgstr "'.$msgid.'"', $entry);
            $entry = preg_replace('/msgid "(.*?)"/', 'msgid "'.$msgstr.'"', $entry);

            $allFile = $allFile . "\n\n". $entry;
        }

        $filePath = storage_path('app/generated.po');
        // Le contenu que vous souhaitez écrire dans le fichier
        $content = $allFile;
        // Écrivez le contenu dans le fichier
        File::put($filePath, $content);

        $content = File::get($filePath);
        $response = response()->download($filePath, 'generated.po');
        return $response;

    }
}



<?php

use App\Classes\ResourceManager;
use App\Models\Language;
use Illuminate\Database\Migrations\Migration;

class AddInitialRecordsToAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){

        // Create language records
        $languages = [
            [
                'code' => 'en',
                'name' => 'English'
            ],
            [
                'code' => 'nl',
                'name' => 'Nederlands'
            ],
        ];
        foreach($languages as $language){
            $Resource = ResourceManager::make('Language');
            $data = [
                'code' => $language['code'],
                'name' => $language['name']
            ];
            $Resource->saveResource($data);

            // Save the English language for later
            if($language['code'] == 'en'){
                $English = $Resource;
            }
        }

        // Find the Dutch language
        $Dutch = Language::where('code', '=', 'nl')->first();

        // Create country records
        $countries = [
            'nl' => [
                [
                    'language' => 'nl',
                    'name' => 'Nederland'
                ],
                [
                    'language' => 'en',
                    'name' => 'Netherlands'
                ]
            ],
            'gb' => [
                [
                    'language' => 'nl',
                    'name' => 'Groot-Brittannië'
                ],
                [
                    'language' => 'en',
                    'name' => 'United Kingdom'
                ]
            ]
        ];
        foreach($countries as $code => $countryTranslations){
            $Resource = ResourceManager::make('Country');
            $data = [
                'code' => $code
            ];
            $Resource->saveResource($data, false);
            
            foreach($countryTranslations as $countryTranslation){
                $Resource->saveTranslation($countryTranslation, $countryTranslation['language']);
            }
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
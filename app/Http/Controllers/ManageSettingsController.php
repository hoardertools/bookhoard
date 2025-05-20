<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class ManageSettingsController extends Controller
{
    public function settingsGeneral(){

        return view('pages.manage.settings.general');

    }

    public function updateGeneralSettings(Request $request){

        $inputData = $request->except('_token');
        foreach ($inputData as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        //Handle checkboxes separately since they are not sent if they are unchecked
        $checkboxes = ['GlobalReadPermissions', 'GlobalWritePermissions', 'GlobalDownloadPermissions'];

        foreach ($checkboxes as $checkbox) {
            if (!isset($inputData[$checkbox])) {
                Setting::where('key', $checkbox)->update(['value' => '0']);
            }
        }

        return redirect()->route('settingsGeneral')
            ->with('status', 'Successfully updated the settings.');

    }

}

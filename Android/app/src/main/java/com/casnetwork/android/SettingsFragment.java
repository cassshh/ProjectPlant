package com.casnetwork.android;

import android.os.Bundle;
import android.preference.PreferenceFragment;

/**
 * Created by Cas on 8-6-2015.
 */
public class SettingsFragment extends PreferenceFragment {
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Load the preferences from an XML resource
        addPreferencesFromResource(R.xml.preferences);
    }
}

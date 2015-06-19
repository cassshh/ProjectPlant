package com.casnetwork.android;

import android.app.ProgressDialog;
import android.os.AsyncTask;
import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.Closeable;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.net.URL;
import java.nio.charset.Charset;

/**
 * Created by Cas on 21-5-2015.
 */
public class JsonData extends AsyncTask<String, Void, JSONObject> {

    private final String url;
    private JSONObject json;
    MyActivity caller = null;
    DisplayMessageActivity callerDis = null;
    UpdateServiceManager.TimeDisplayTimerTask callerUpdate = null;
    ProgressDialog pDialog = null;

    //Constuctors
    public JsonData(MyActivity caller, String url) {
        this.caller = caller;
        this.url = url;
    }
    public JsonData(DisplayMessageActivity caller, String url) {
        this.callerDis = caller;
        this.url = url;
    }
    public JsonData(UpdateServiceManager.TimeDisplayTimerTask caller, String url) {
        this.callerUpdate = caller;
        this.url = url;
    }

    @Override
    protected void onPreExecute() {
        super.onPreExecute();
        // Showing progress dialog
        if (caller != null) { //set pDialog
            pDialog = new ProgressDialog(caller);
            pDialog.setMessage("Please wait...");
            pDialog.setCancelable(true);
            pDialog.show();
        } else if(callerDis != null){
            pDialog = new ProgressDialog(callerDis);
            pDialog.setMessage("Please wait...");
            pDialog.setCancelable(true);
            pDialog.show();
        }

    }

    protected JSONObject doInBackground(String... urls) {
        try {
            json = readJsonFromUrl(url);
            return json;
        } catch (IOException e) {
            return null;
        } catch (JSONException e) {
            return null;
        }
    }

    private static String readAll(Reader rd) throws IOException {
        StringBuilder sb = new StringBuilder();
        int cp;
        while ((cp = rd.read()) != -1) {
            sb.append((char) cp);
        }
        return sb.toString();
    }

    private static JSONObject readJsonFromUrl(String url) throws IOException, JSONException {
        InputStream is = new URL(url).openStream();
        try {
            BufferedReader rd = new BufferedReader(new InputStreamReader(is, Charset.forName("UTF-8")));
            String jsonText = readAll(rd);
            JSONObject json = new JSONObject(jsonText);
            return json;
        } finally {
            is.close();
        }
    }

    protected void onPostExecute(JSONObject json) { //invoke updateView method
        if (caller != null) caller.updateView(json);
        if (callerDis != null) callerDis.updateView(json);
        if (callerUpdate != null) callerUpdate.updateView(json);
        if (pDialog != null) if (pDialog.isShowing()) pDialog.dismiss();
    }
}

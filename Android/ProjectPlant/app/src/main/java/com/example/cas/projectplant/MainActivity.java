package com.example.cas.projectplant;

import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.os.AsyncTask;
import android.app.Activity;
import android.widget.LinearLayout;
import android.widget.TextView;


public class MainActivity extends Activity {

    LinearLayout layout;

    //TextView valueTV;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        layout = (LinearLayout)findViewById(R.id.info);
        //valueTV = new TextView(this);

        new AsyncTaskParseJson().execute();

    }

    // you can make this class as another java file so it will be separated from your main activity.
    public class AsyncTaskParseJson extends AsyncTask<String, String, String> {

        final String TAG = "AsyncTaskParseJson.java";

        // set your json string url here
        String yourJsonStringUrl = "http://plant.serverict.nl/data.php";

        // contacts JSONArray
        JSONArray dataJsonArr = null;

        @Override
        protected void onPreExecute() {}

        @Override
        protected String doInBackground(String... arg0) {

            try {

                // instantiate our json parser
                JsonParser jParser = new JsonParser();

                // get json string from url
                JSONObject json = jParser.getJSONFromUrl(yourJsonStringUrl);

                // get the array of users
                dataJsonArr = json.getJSONArray("Data");

                // loop through all users
                for (int i = 0; i < dataJsonArr.length(); i++) {

                    JSONObject c = dataJsonArr.getJSONObject(i);

                    // Storing each json item in variable
                    String type = c.getString("plantType");
                    String minTemp = c.getString("plantMinTemp");
                    String maxTemp = c.getString("plantMaxTemp");
                    String minLight = c.getString("plantMinLicht");
                    String maxLight = c.getString("plantMaxLicht");
                    String minMoist = c.getString("plantMinVochtigheid");
                    String maxMoist = c.getString("plantMaxVochtigheid");

                    // show the values in our logcat
                    /*Log.e(TAG, "id: " + firstname
                            + ", user_id: " + lastname
                            + ", message: " + username);*/


                    //Create TextView per data
                    //LinearLayout linearLayout = (LinearLayout)findViewById(R.id.info);
                    LinearLayout layoutData = new LinearLayout(layout.getContext());
                    TextView valueTV = new TextView(layout.getContext());
                    valueTV.setText("Type: " + type
                            + ",\n minTemp: " + minTemp
                            + ",\n maxTemp: " + maxTemp
                            + ",\n minLight: " + minLight
                            + ",\n maxLight: " + maxTemp
                            + ",\n minMoist: " + minMoist
                            + ",\n maxMoist: " + maxMoist);
                    valueTV.setTypeface(null, Typeface.BOLD);
                    //valueTV.setId(5);
                    valueTV.setLayoutParams(new LinearLayout.LayoutParams(
                            LinearLayout.LayoutParams.WRAP_CONTENT,
                            LinearLayout.LayoutParams.WRAP_CONTENT));

                    layout.addView(layoutData);
                    layoutData.addView(valueTV);
                    valueTV.setBackgroundColor(Color.parseColor("#aaffaa"));
                    layoutData.setPadding(10, 10, 10, 10);
                    valueTV.setPadding(10, 10, 10, 10);
                    layoutData.setBackgroundColor(Color.parseColor("#aaaaff"));
                    layout.setBackgroundColor(Color.parseColor("#ffaaaa"));
                }

            } catch (JSONException e) {
                e.printStackTrace();
            }

            return null;
        }

        @Override
        protected void onPostExecute(String strFromDoInBg) {}
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }
}

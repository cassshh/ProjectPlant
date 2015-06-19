package com.casnetwork.android;

import android.app.Activity;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.TaskStackBuilder;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.support.v4.app.NotificationCompat;
import android.support.v7.widget.CardView;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;


public class MyActivity extends Activity {

    public static final String EXTRA_MESSAGE = "com.casnetwork.MESSAGE";
    LinearLayout linearLayout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_my);
        linearLayout = (LinearLayout) findViewById(R.id.layout);
        //getActionBar().setDisplayHomeAsUpEnabled(true);
        new JsonData(this, "http://casnetwork.tk/plant/data.php").execute(); //Execute retreiving data

        startService(new Intent(this, UpdateServiceManager.class)); //Start notification service
    }

    @Override
    protected void onDestroy(){
        getIntent().removeExtra(EXTRA_MESSAGE);
        super.onDestroy();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.main_activity_actions, menu);
        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle presses on the action bar items
        switch (item.getItemId()) {
            case R.id.action_refresh: //Update data
                new JsonData(this, "http://casnetwork.tk/plant/data.php").execute();
                return true;
            case R.id.action_settings: //Open settings
                Intent i = new Intent(MyActivity.this, SettingsActivity.class);
                startActivity(i);
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    public void updateView(JSONObject json){
        if(json != null) {
            try {
                setContentView(R.layout.activity_my);
                linearLayout = (LinearLayout) findViewById(R.id.layout);
                //Log.d("updateView", json.getString("Data")); //Log for debugging

                JSONArray jsonArray = json.getJSONArray("Data");
                //Loop through Data array
                for (int i = 0; i < jsonArray.length(); i++) {
                    LinearLayout child = (LinearLayout) getLayoutInflater().inflate(R.layout.card_view, null);
                    final CardView childCard = (CardView) child.findViewById(R.id.card_view);
                    childCard.setBackgroundColor(Color.parseColor("#2196F3"));
                    //LinearLayout childLinear = (LinearLayout) childCard.findViewById(R.id.inner_linear);

                    //Assign all the elements
                    final TextView txID = (TextView) child.findViewById(R.id.text_id);
                    TextView txName = (TextView) child.findViewById(R.id.text_name);
                    TextView txTypeName = (TextView) child.findViewById(R.id.text_type);
                    TextView txTemp = (TextView) child.findViewById(R.id.text_temp);
                    TextView txLight = (TextView) child.findViewById(R.id.text_light);
                    TextView txMoist = (TextView) child.findViewById(R.id.text_moist);
                    TextView txLastdate = (TextView) child.findViewById(R.id.text_lastdate);

                    ProgressBar pbTemp = (ProgressBar) child.findViewById(R.id.pb_temp);
                    ProgressBar pbLight = (ProgressBar) child.findViewById(R.id.pb_light);
                    ProgressBar pbMoist = (ProgressBar) child.findViewById(R.id.pb_moist);
                    try {
                        JSONObject c = jsonArray.getJSONObject(i);
                        txID.setText(c.getString("plant_id"));
                        txName.setText(c.getString("name"));

                        //Default values
                        int maxTemp = 100;
                        int maxLight = 100;
                        int maxMoist = 100;
                        int minTemp = 0;
                        int minLight = 0;
                        int minMoist = 0;

                        JSONArray jAr = c.getJSONArray("type"); //'type' array
                        for (int j = 0; j < jAr.length(); j++) { //Get 'type' min & max
                            try {
                                JSONObject d = jAr.getJSONObject(j);
                                txTypeName.setText(d.getString("name"));

                                maxTemp = d.getInt("maxTemp");
                                maxLight = d.getInt("maxLight");
                                maxMoist = d.getInt("maxMoist");
                                minTemp = d.getInt("minTemp");
                                minLight = d.getInt("minLight");
                                minMoist = d.getInt("minMoist");
                                pbTemp.setMax(maxTemp);
                                pbLight.setMax(maxLight);
                                pbMoist.setMax(maxMoist);

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                        JSONArray jArr = c.getJSONArray("lastData"); //'lastData' array
                        for (int j = 0; j < jArr.length(); j++) { //Set texts & progressbars
                            try {
                                JSONObject d = jArr.getJSONObject(j);
                                txTemp.setText("Temp: " + d.getString("temp"));
                                txLight.setText("Light: " + d.getString("light"));
                                txMoist.setText("Moist: " + d.getString("moist"));
                                txLastdate.setText(d.getString("dateTime"));

                                //Set values
                                int temp = d.getInt("temp");
                                int light = d.getInt("light");
                                int moist = d.getInt("moist");

                                //Create progressbars
                                new ProgressBarStatus(pbTemp, minTemp, maxTemp, temp).setProgressBar();
                                new ProgressBarStatus(pbLight, minLight, maxLight, light).setProgressBar();
                                new ProgressBarStatus(pbMoist, minMoist, maxMoist, moist).setProgressBar();

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                    linearLayout.addView(child); //Add everything to the layout
                    linearLayout.setOrientation(LinearLayout.VERTICAL);

                    //Click events
                    childCard.setOnClickListener(new View.OnClickListener() { //Click event per cardview
                        @Override
                        public void onClick(View v) {
                            Intent intent = new Intent(MyActivity.this, DisplayMessageActivity.class);
                            String message = txID.getText().toString();
                            intent.putExtra(EXTRA_MESSAGE, message); //Pass ID of plant with it
                            startActivity(intent);
                        }
                    });
                }
                Toast.makeText(this, "Refreshed", Toast.LENGTH_SHORT).show();
            } catch (JSONException e) {
                e.printStackTrace();
            }
        } else {
            Toast.makeText(this, "Could not refresh", Toast.LENGTH_SHORT).show();
        }
    }
}

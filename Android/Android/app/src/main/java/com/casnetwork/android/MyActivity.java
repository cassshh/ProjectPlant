package com.casnetwork.android;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.AsyncTask;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.support.v7.widget.CardView;
import android.util.JsonReader;
import android.util.Log;
import android.view.DragEvent;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.w3c.dom.Text;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.net.URL;
import java.nio.charset.Charset;


public class MyActivity extends Activity {

    public static final String EXTRA_MESSAGE = "com.casnetwork.MESSAGE";
    LinearLayout linearLayout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_my);
        linearLayout = (LinearLayout) findViewById(R.id.layout);
        //getActionBar().setDisplayHomeAsUpEnabled(true);
        new JsonData(this, "http://plant.serverict.nl/data.php").execute();
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
            case R.id.action_search:
                //openSearch();
                new JsonData(this, "http://plant.serverict.nl/data.php").execute();
                Toast.makeText(this, "Refreshed", Toast.LENGTH_SHORT).show();
                return true;
            case R.id.action_settings:
                //openSettings();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    /*public void sendMessage(View view){
        Intent intent = new Intent(this, DisplayMessageActivity.class);
        EditText editText = (EditText)findViewById(R.id.edit_message);
        String message = editText.getText().toString();
        intent.putExtra(EXTRA_MESSAGE, message);
        startActivity(intent);
    }*/

    public void updateView(JSONObject json){
        if(json != null) {
            try {
                Log.d("updateView", json.getString("Data"));

                JSONArray jsonArray = json.getJSONArray("Data");
                //Loop through Data array
                for (int i = 0; i < jsonArray.length(); i++) {
                    LinearLayout child = (LinearLayout) getLayoutInflater().inflate(R.layout.card_view, null);
                    final CardView childCard = (CardView) child.findViewById(R.id.card_view);
                    childCard.setBackgroundColor(Color.parseColor("#2196F3"));
                    LinearLayout childLinear = (LinearLayout) childCard.findViewById(R.id.inner_linear);

                    final TextView txID = new TextView(this);
                    TextView txName = new TextView(this);
                    TextView txTypeName = new TextView(this);
                    try {
                        JSONObject c = jsonArray.getJSONObject(i);
                        txID.setText(c.getString("plant_id"));
                        txName.setText(c.getString("name"));
                        JSONArray jAr = c.getJSONArray("type");
                        for (int j = 0; j < jAr.length(); j++) {
                            try {
                                JSONObject d = jAr.getJSONObject(j);
                                txTypeName.setText(d.getString("name"));
                            } catch (JSONException e) {
                            }
                        }
                        childLinear.addView(txID);
                        childLinear.addView(txName);
                        childLinear.addView(txTypeName);
                        txID.setPadding(10, 10, 10, 10);
                        txName.setPadding(10, 10, 10, 10);
                        txTypeName.setPadding(10, 10, 10, 10);
                    } catch (JSONException e) {
                    }
                    linearLayout.addView(child);
                    linearLayout.setOrientation(LinearLayout.VERTICAL);
                    //child.setPadding(10,10,10,10);
                    //child.setBackgroundColor(Color.parseColor("#ddeedd"));
                    //child.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT));

                    childCard.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View v) {
                            Intent intent = new Intent(MyActivity.this, DisplayMessageActivity.class);
                            String message = txID.getText().toString();
                            intent.putExtra(EXTRA_MESSAGE, message);
                            startActivity(intent);
                        }
                    });
                }
            } catch (JSONException e) {
                //
            }
        }
    }
}

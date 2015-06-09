package com.casnetwork.android;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.text.Layout;
import android.util.Log;
import android.util.TypedValue;
import android.view.MenuItem;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.jjoe64.graphview.GraphView;
import com.jjoe64.graphview.helper.DateAsXAxisLabelFormatter;
import com.jjoe64.graphview.series.DataPoint;
import com.jjoe64.graphview.series.LineGraphSeries;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;


public class DisplayMessageActivity extends Activity {

    LinearLayout linearLayout;
    //GraphView graph;
    boolean axisZero;
    boolean minmax;
    String timeSpan;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_display_message);
        Intent intent = getIntent();
        String message = intent.getStringExtra(MyActivity.EXTRA_MESSAGE);

        linearLayout = (LinearLayout) findViewById(R.id.layout);
        //graph = (GraphView) findViewById(R.id.graph);

        new JsonData(this, "http://casnetwork.tk/plant/data.php?id=" + message).execute();

        SharedPreferences pref = PreferenceManager.getDefaultSharedPreferences(getBaseContext());
        axisZero = pref.getBoolean("pref_start_zero", true);
        minmax = pref.getBoolean("pref_min_max", true);
        timeSpan =  pref.getString("pref_time_span", "1");

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

    public void updateView(JSONObject json) {
        if(json != null) {
            try {
                Log.d("updateViewData", json.getString("Data"));
                Log.d("updateViewGraph", json.getString("Graph"));

                double tempMin = 0,
                        tempMax = 0,
                        lightMin = 0,
                        lightMax = 0,
                        moistMin = 0,
                        moistMax = 0;

                JSONArray jsonArrayData = json.getJSONArray("Data");
                JSONArray jsonArrayType = json.getJSONArray("Type");
                JSONArray jsonArrayGraph = json.getJSONArray("Graph");
                //Loop through Data array
                if(jsonArrayData.length() != 0) {
                    for (int i = 0; i < jsonArrayData.length(); i++) {
                        //code
                        try {
                            JSONObject c = jsonArrayData.getJSONObject(i);
                            getActionBar().setTitle(c.getString("name") + " Data");
                        }catch (JSONException e){
                            //code
                        }
                    }
                }
                if(jsonArrayType.length() != 0) {
                    for (int i = 0; i < jsonArrayType.length(); i++) {
                        LinearLayout linLay = new LinearLayout(this);
                        TextView txID = new TextView(this);
                        TextView txName = new TextView(this);
                        TextView txMinTemp = new TextView(this);
                        TextView txMaxTemp = new TextView(this);
                        TextView txMinLight = new TextView(this);
                        TextView txMaxLight = new TextView(this);
                        TextView txMinMoist = new TextView(this);
                        TextView txMaxMoist = new TextView(this);
                        try {
                            JSONObject c = jsonArrayType.getJSONObject(i);
                            txID.setText("Type ID: " + c.getString("type_id"));
                            txID.setPadding(10, 10, 10, 10);
                            txName.setText("Name: " + c.getString("name"));
                            txName.setPadding(10, 10, 10, 10);
                            tempMin = c.getDouble("minTemp");
                            txMinTemp.setText("Min Temp: " + c.getString("minTemp"));
                            txMinTemp.setPadding(10, 10, 10, 10);
                            tempMax = c.getDouble("maxTemp");
                            txMaxTemp.setText("Max Temp: " + c.getString("maxTemp"));
                            txMaxTemp.setPadding(10, 10, 10, 10);
                            lightMin = c.getDouble("minLight");
                            txMinLight.setText("Min Light: " + c.getString("minLight"));
                            txMinLight.setPadding(10, 10, 10, 10);
                            lightMax = c.getDouble("maxLight");
                            txMaxLight.setText("Max Light: " + c.getString("maxLight"));
                            txMaxLight.setPadding(10, 10, 10, 10);
                            moistMin = c.getDouble("minMoist");
                            txMinMoist.setText("Min Moist: " + c.getString("minMoist"));
                            txMinMoist.setPadding(10, 10, 10, 10);
                            moistMax = c.getDouble("maxMoist");
                            txMaxMoist.setText("Max Moist: " + c.getString("maxMoist"));
                            txMaxMoist.setPadding(10, 10, 10, 10);

                            linLay.addView(txID);
                            linLay.addView(txName);
                            /*linLay.addView(txMinTemp);
                            linLay.addView(txMaxTemp);
                            linLay.addView(txMinLight);
                            linLay.addView(txMaxLight);
                            linLay.addView(txMinMoist);
                            linLay.addView(txMaxMoist);*/
                            linearLayout.addView(linLay);
                            linLay.setOrientation(LinearLayout.VERTICAL);
                            linLay.setPadding(40, 40, 40, 40);
                            //linLay.setBackgroundColor(Color.parseColor("#ddeedd"));
                        } catch (JSONException e) {
                            //
                        }

                    }
                }
                if(jsonArrayGraph.length() != 0) {
                    int height = (int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 200, getResources().getDisplayMetrics());

                    GraphView graphTemp = new GraphView(this);
                    graphTemp.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, height));
                    GraphView graphLight = new GraphView(this);
                    graphLight.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, height));
                    GraphView graphMoist = new GraphView(this);
                    graphMoist.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, height));

                    LineGraphSeries<DataPoint> seriesTemp = new LineGraphSeries<>();
                    LineGraphSeries<DataPoint> seriesTempMax = new LineGraphSeries<>();
                    LineGraphSeries<DataPoint> seriesTempMin = new LineGraphSeries<>();
                    LineGraphSeries<DataPoint> seriesLight = new LineGraphSeries<>();
                    LineGraphSeries<DataPoint> seriesLightMax = new LineGraphSeries<>();
                    LineGraphSeries<DataPoint> seriesLightMin = new LineGraphSeries<>();
                    LineGraphSeries<DataPoint> seriesMoist = new LineGraphSeries<>();
                    LineGraphSeries<DataPoint> seriesMoistMax = new LineGraphSeries<>();
                    LineGraphSeries<DataPoint> seriesMoistMin = new LineGraphSeries<>();

                    Calendar calendar = Calendar.getInstance();
                    Date d = calendar.getTime();

                    for (int i = 0; i < jsonArrayGraph.length(); i++) {
                        try {
                            JSONObject c = jsonArrayGraph.getJSONObject(i);
                            SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
                            try {
                                Date date = format.parse(c.getString("dateTime"));
                                if (i == jsonArrayGraph.length() - 1) {
                                    calendar.setTime(date);
                                    d = calendar.getTime();
                                    //Toast.makeText(this, "Last Time: " + d.toString(), Toast.LENGTH_LONG).show();
                                    //Toast.makeText(this, Integer.toString(calendar.get(Calendar.HOUR_OF_DAY)), Toast.LENGTH_SHORT).show();
                                }
                                DataPoint dpTemp = new DataPoint(date.getTime(), c.getDouble("temp"));
                                seriesTemp.appendData(dpTemp, true, jsonArrayGraph.length());
                                DataPoint dpLight = new DataPoint(date.getTime(), c.getDouble("light"));
                                seriesLight.appendData(dpLight, true, jsonArrayGraph.length());
                                DataPoint dpMoist = new DataPoint(date.getTime(), c.getDouble("moist"));
                                seriesMoist.appendData(dpMoist, true, jsonArrayGraph.length());

                                DataPoint dpTempMax = new DataPoint(date.getTime(), tempMax);
                                seriesTempMax.appendData(dpTempMax, true, jsonArrayGraph.length());
                                seriesTempMax.setColor(Color.RED);
                                DataPoint dpTempMin = new DataPoint(date.getTime(), tempMin);
                                seriesTempMin.appendData(dpTempMin, true, jsonArrayGraph.length());
                                seriesTempMin.setColor(Color.RED);
                                DataPoint dpLightMax = new DataPoint(date.getTime(), lightMax);
                                seriesLightMax.appendData(dpLightMax, true, jsonArrayGraph.length());
                                seriesLightMax.setColor(Color.RED);
                                DataPoint dpLightMin = new DataPoint(date.getTime(), lightMin);
                                seriesLightMin.appendData(dpLightMin, true, jsonArrayGraph.length());
                                seriesLightMin.setColor(Color.RED);
                                DataPoint dpMoistMax = new DataPoint(date.getTime(), moistMax);
                                seriesMoistMax.appendData(dpMoistMax, true, jsonArrayGraph.length());
                                seriesMoistMax.setColor(Color.RED);
                                DataPoint dpMoistMin = new DataPoint(date.getTime(), moistMin);
                                seriesMoistMin.appendData(dpMoistMin, true, jsonArrayGraph.length());
                                seriesMoistMin.setColor(Color.RED);
                            } catch (ParseException e) {
                                // TODO Auto-generated catch block
                                e.printStackTrace();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                    //Toast.makeText(this, d.toString(), Toast.LENGTH_LONG).show();
                    graphTemp.addSeries(seriesTemp);
                    graphTemp.getGridLabelRenderer().setLabelFormatter(new DateAsXAxisLabelFormatter(this));
                    graphTemp.getViewport().setXAxisBoundsManual(true);
                    graphLight.addSeries(seriesLight);
                    graphLight.getGridLabelRenderer().setLabelFormatter(new DateAsXAxisLabelFormatter(this));
                    graphLight.getViewport().setXAxisBoundsManual(true);
                    graphMoist.addSeries(seriesMoist);
                    graphMoist.getGridLabelRenderer().setLabelFormatter(new DateAsXAxisLabelFormatter(this));
                    graphMoist.getViewport().setXAxisBoundsManual(true);

                    if(minmax){
                        graphTemp.addSeries(seriesTempMax);
                        graphTemp.addSeries(seriesTempMin);
                        graphLight.addSeries(seriesLightMax);
                        graphLight.addSeries(seriesLightMin);
                        graphMoist.addSeries(seriesMoistMax);
                        graphMoist.addSeries(seriesMoistMin);
                    }

                    if(axisZero) {
                        graphTemp.getViewport().setYAxisBoundsManual(true);
                        graphTemp.getViewport().setMinY(0);
                        graphLight.getViewport().setYAxisBoundsManual(true);
                        graphLight.getViewport().setMinY(0);
                        graphMoist.getViewport().setYAxisBoundsManual(true);
                        graphMoist.getViewport().setMinY(0);
                    }

                    calendar.setTime(d);
                    switch (timeSpan){
                        case "1":
                            calendar.add(Calendar.HOUR, -1);
                            break;
                        case "2":
                            calendar.add(Calendar.DATE, -1);
                            break;
                        case "3":
                            calendar.add(Calendar.DATE, -6);
                            break;
                    }
                    graphTemp.getViewport().setMinX(calendar.getTime().getTime());
                    graphLight.getViewport().setMinX(calendar.getTime().getTime());
                    graphMoist.getViewport().setMinX(calendar.getTime().getTime());
                    calendar.setTime(d);
                    switch (timeSpan){
                        case "1":
                            calendar.add(Calendar.MINUTE, 15);
                            break;
                        case "2":
                            calendar.add(Calendar.HOUR, 1);
                            break;
                        case "3":
                            calendar.add(Calendar.HOUR, 3);
                            break;
                    }
                    graphTemp.getViewport().setMaxX(calendar.getTime().getTime());
                    graphTemp.getGridLabelRenderer().setNumHorizontalLabels(3);
                    graphTemp.setVerticalScrollBarEnabled(true);
                    graphLight.getViewport().setMaxX(calendar.getTime().getTime());
                    graphLight.getGridLabelRenderer().setNumHorizontalLabels(3);
                    graphLight.setVerticalScrollBarEnabled(true);
                    graphMoist.getViewport().setMaxX(calendar.getTime().getTime());
                    graphMoist.getGridLabelRenderer().setNumHorizontalLabels(3);
                    graphMoist.setVerticalScrollBarEnabled(true);

                    graphTemp.setTitle("Temperature");
                    graphLight.setTitle("Light");
                    graphMoist.setTitle("Moist");

                    linearLayout.addView(graphTemp);
                    linearLayout.addView(graphLight);
                    linearLayout.addView(graphMoist);

                }
            } catch (JSONException e) {
                //
            }

        }

    }
}

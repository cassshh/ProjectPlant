package com.casnetwork.android;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.provider.ContactsContract;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.util.TypedValue;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
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


public class DisplayMessageActivity extends Activity {

    LinearLayout linearLayout;
    //GraphView graph;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_display_message);
        Intent intent = getIntent();
        String message = intent.getStringExtra(MyActivity.EXTRA_MESSAGE);

        linearLayout = (LinearLayout) findViewById(R.id.layout);
        //graph = (GraphView) findViewById(R.id.graph);

        new JsonData(this, "http://casnetwork.tk/plant/data.php?id=" + message).execute();
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
                            //txID.setBackgroundColor(Color.parseColor("#ddffdd"));
                            txName.setText("Name: " + c.getString("name"));
                            txName.setPadding(10, 10, 10, 10);
                            //txName.setBackgroundColor(Color.parseColor("#ddffdd"));
                            txMinTemp.setText("Min Temp: " + c.getString("minTemp"));
                            txMinTemp.setPadding(10, 10, 10, 10);
                            //txMinTemp.setBackgroundColor(Color.parseColor("#ddffdd"));
                            txMaxTemp.setText("Max Temp: " + c.getString("maxTemp"));
                            txMaxTemp.setPadding(10, 10, 10, 10);
                            //txMaxTemp.setBackgroundColor(Color.parseColor("#ddffdd"));
                            txMinLight.setText("Min Light: " + c.getString("minLight"));
                            txMinLight.setPadding(10, 10, 10, 10);
                            //txMinLight.setBackgroundColor(Color.parseColor("#ddffdd"));
                            txMaxLight.setText("Max Light: " + c.getString("maxLight"));
                            txMaxLight.setPadding(10, 10, 10, 10);
                            //txMaxLight.setBackgroundColor(Color.parseColor("#ddffdd"));
                            txMinMoist.setText("Min Moist: " + c.getString("minMoist"));
                            txMinMoist.setPadding(10, 10, 10, 10);
                            //txMinMoist.setBackgroundColor(Color.parseColor("#ddffdd"));
                            txMaxMoist.setText("Max Moist: " + c.getString("maxMoist"));
                            txMaxMoist.setPadding(10, 10, 10, 10);
                            //txMaxMoist.setBackgroundColor(Color.parseColor("#ddffdd"));

                            linLay.addView(txID);
                            linLay.addView(txName);
                            linLay.addView(txMinTemp);
                            linLay.addView(txMaxTemp);
                            linLay.addView(txMinLight);
                            linLay.addView(txMaxLight);
                            linLay.addView(txMinMoist);
                            linLay.addView(txMaxMoist);
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
                    LineGraphSeries<DataPoint> seriesLight = new LineGraphSeries<>();
                    LineGraphSeries<DataPoint> seriesMoist = new LineGraphSeries<>();

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
                                }
                                DataPoint dpTemp = new DataPoint(date.getTime(), c.getDouble("temp"));
                                seriesTemp.appendData(dpTemp, true, 10);
                                DataPoint dpLight = new DataPoint(date.getTime(), c.getDouble("light"));
                                seriesLight.appendData(dpLight, true, 10);
                                DataPoint dpMoist = new DataPoint(date, c.getDouble("moist"));
                                seriesMoist.appendData(dpMoist, true, 10);
                            } catch (ParseException e) {
                                // TODO Auto-generated catch block
                                e.printStackTrace();
                            }
                        } catch (JSONException e) {

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

                    calendar.setTime(d);
                    calendar.add(Calendar.MINUTE, -10);
                    graphTemp.getViewport().setMinX(calendar.getTime().getTime());
                    graphLight.getViewport().setMinX(calendar.getTime().getTime());
                    graphMoist.getViewport().setMinX(calendar.getTime().getTime());
                    calendar.setTime(d);
                    calendar.add(Calendar.MINUTE, 5);
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

                    graphTemp.setPadding(0, 0, 0, 0);
                    graphLight.setPadding(0,0,0,0);
                    graphMoist.setPadding(0,0,0,0);
                    linearLayout.addView(graphTemp);
                    linearLayout.addView(graphLight);
                    linearLayout.addView(graphMoist);
                }
            } catch (JSONException e) {
                //
            }
        }
/*        Calendar calendar = Calendar.getInstance();
        Date d1 = calendar.getTime();
        calendar.add(Calendar.DATE, 1);
        Date d2 = calendar.getTime();
        calendar.add(Calendar.DATE, 1);
        Date d3 = calendar.getTime();
        LineGraphSeries<DataPoint> series = new LineGraphSeries<DataPoint>(new DataPoint[] {
                new DataPoint(d1, 1),
                new DataPoint(d2, 5),
                new DataPoint(d3, 3)
        });*/
        /*graph.addSeries(series);

        graph.getGridLabelRenderer().setLabelFormatter(new DateAsXAxisLabelFormatter(this));
        graph.getGridLabelRenderer().setNumHorizontalLabels(3);
        graph.getViewport().setMinX(d1.getTime());
        graph.getViewport().setMaxX(d3.getTime());
        graph.getViewport().setXAxisBoundsManual(true);*/
    }
}

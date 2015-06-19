package com.casnetwork.android;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.app.TaskStackBuilder;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Handler;
import android.os.IBinder;
import android.preference.PreferenceManager;
import android.support.v4.app.NotificationCompat;
import android.util.Log;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Timer;
import java.util.TimerTask;

/**
 * Created by Cas on 9-6-2015.
 */
public class UpdateServiceManager extends Service {
    // constant
    public static final long NOTIFY_INTERVAL = 5 * 1000; // Update every 5 seconds

    // run on another Thread to avoid crash
    private Handler mHandler = new Handler();
    // timer handling
    private Timer mTimer = null;

    SharedPreferences pref;
    Boolean notify = true;

    ArrayList<Integer> lastArray = new ArrayList<>();
    ArrayList<String> lastMessage = new ArrayList<>();
    String fineNotify, tempNotify, lightNotify, moistNotify, temp_lightNotify, temp_moistNotify, light_moistNotify, temp_light_moistNotify;

    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }

    @Override
    public void onCreate() {
        // cancel if already existed
        if (mTimer != null) {
            mTimer.cancel();
        } else {
            // recreate new
            mTimer = new Timer();
        }
        // schedule task
        mTimer.scheduleAtFixedRate(new TimeDisplayTimerTask(), 0, NOTIFY_INTERVAL);
        Context ctx = getApplicationContext();
        pref = PreferenceManager.getDefaultSharedPreferences(ctx); //Get preferences (settings)
    }

    class TimeDisplayTimerTask extends TimerTask {

        @Override
        public void run() {
            // run on another thread
            mHandler.post(new Runnable() {

                @Override
                public void run() {
                    //Get all the preferences in settings, if not exist, default value
                    notify = pref.getBoolean("pref_notify", true);
                    fineNotify = pref.getString("pref_fine", "");
                    tempNotify = pref.getString("pref_temp", "Prr check out mah temp");
                    lightNotify = pref.getString("pref_light", "Check out the light");
                    moistNotify = pref.getString("pref_moist", "Check my ground moist");
                    temp_lightNotify = pref.getString("pref_temp_light", "Temperature and light pls");
                    temp_moistNotify = pref.getString("pref_temp_light", "Temperature and moist are not ok");
                    light_moistNotify = pref.getString("pref_light_moist", "Light and moist pls");
                    temp_light_moistNotify = pref.getString("pref_temp_light_light", "I think i just died");
                    if (notify) { //if notification are turned on
                        new JsonData(UpdateServiceManager.TimeDisplayTimerTask.this, "http://casnetwork.tk/plant/data.php?notification").execute();
                    }
                }

            });
        }

        public void updateView(JSONObject json) {
            if (json != null) {
                try {
                    JSONArray notifyArray = json.getJSONArray("Notify");
                    if (notifyArray.length() != 0) {
                        for (int i = 0; i < notifyArray.length(); i++) { //Loop through JsonArray
                            //code
                            try { //to prevent error with IndexOutOfBound
                                lastArray.get(i);
                            } catch (IndexOutOfBoundsException e){
                                lastArray.add(i, 0);
                            }
                            try { //to prevent error with IndexOutOfBound
                                lastMessage.get(i);
                            }catch (IndexOutOfBoundsException e){
                                lastMessage.add(i, "");
                            }
                            try {
                                JSONObject c = notifyArray.getJSONObject(i);

                                String name = c.getString("name");

                                JSONArray typeArray = c.getJSONArray("type");
                                JSONArray dataArray = c.getJSONArray("data");
                                if (typeArray.length() != 0 && dataArray.length() != 0) {
                                    //Compare data
                                    Double minTemp = null,
                                            maxTemp = null,
                                            temp = null,
                                            minLight = null,
                                            maxLight = null,
                                            light = null,
                                            minMoist = null,
                                            maxMoist = null,
                                            moist = null;
                                    for (int j = 0; j < typeArray.length(); j++) { //Loop through 'type' array
                                        JSONObject type = typeArray.getJSONObject(j);
                                        minTemp = type.getDouble("minTemp");
                                        maxTemp = type.getDouble("maxTemp");
                                        minLight = type.getDouble("minLight");
                                        maxLight = type.getDouble("maxLight");
                                        minMoist = type.getDouble("minMoist");
                                        maxMoist = type.getDouble("maxMoist");
                                    }
                                    for (int j = 0; j < dataArray.length(); j++) { //Loop through 'data' array
                                        JSONObject data = dataArray.getJSONObject(j);
                                        temp = data.getDouble("temp");
                                        light = data.getDouble("light");
                                        moist = data.getDouble("moist");
                                    }

                                    //check which message should be displayed
                                    int mes = 0;
                                    if (temp != null && maxTemp != null && minTemp != null) {
                                        if (temp > maxTemp || temp < minTemp) {
                                            mes = 1;
                                        }
                                    }
                                    if (light != null && maxLight != null && minLight != null) {
                                        if (light > maxLight || light < minLight) {
                                            if (mes == 1) {
                                                mes = 4;
                                            } else {
                                                mes = 2;
                                            }
                                        }
                                    }
                                    if (moist != null && maxMoist != null && minMoist != null) {
                                        if (moist > maxMoist || moist < minMoist) {
                                            if (mes == 1) {
                                                mes = 5;
                                            } else if (mes == 2) {
                                                mes = 6;
                                            } else if (mes == 4) {
                                                mes = 7;
                                            } else {
                                                mes = 3;
                                            }
                                        }
                                    }
                                    if (lastArray.get(i) != mes) { //Check is message is different to prevent notification spam
                                        lastArray.set(i, mes);
                                        String message = "";
                                        switch (lastArray.get(i)) {
                                            case 0:
                                                //No mess
                                                message = fineNotify;
                                                break;
                                            case 1:
                                                //Temp not ok
                                                message = tempNotify;
                                                break;
                                            case 2:
                                                //Light not ok
                                                message = lightNotify;
                                                break;
                                            case 3:
                                                //Moist not ok
                                                message = moistNotify;
                                                break;
                                            case 4:
                                                //Temp & Light not ok
                                                message = temp_lightNotify;
                                                break;
                                            case 5:
                                                //Temp & Moist not ok
                                                message = temp_moistNotify;
                                                break;
                                            case 6:
                                                //Light & Moist not ok
                                                message = light_moistNotify;
                                                break;
                                            case 7:
                                                //Nothing is ok...
                                                message = temp_light_moistNotify;
                                                break;
                                        }
                                        if(!message.isEmpty()) {
                                            lastMessage.set(i, name + ": " + message);
                                        } else {
                                            lastMessage.set(i, "");
                                        }
                                        createNotification(); //Call notification method
                                    }
                                }
                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }

        public void createNotification(){
            String bigText = "";
            boolean cancelNotify = false;
            for(int i = 0; i < lastArray.size(); i++){
                if(!lastMessage.get(i).isEmpty()){
                    Log.d("Message", lastMessage.get(i));
                    bigText += lastMessage.get(i) + ".\n";
                    cancelNotify = true;
                }
            }
            if(!cancelNotify){ //If no more messages -> cancelnotify = false -> calcel notification
                NotificationManager notifManager= (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
                notifManager.cancelAll();
            } else { //Else create/update notification
                NotificationManager mNotificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
                // Sets an ID for the notification, so it can be updated
                int notifyID = 1;
                NotificationCompat.Builder mNotifyBuilder = new NotificationCompat.Builder(UpdateServiceManager.this)
                        .setContentTitle("Weed Plantie") //Title
                        .setContentText("Scroll down for info") //Text
                        .setSmallIcon(R.drawable.notify_icon) //Icon
                        .setDefaults(Notification.DEFAULT_ALL)
                        .setPriority(NotificationCompat.PRIORITY_HIGH)
                        .setColor(Color.parseColor("#1565C0")) //Color background
                        .setStyle(new NotificationCompat.BigTextStyle()
                                .bigText(bigText)) //Bigtext, generated above
                        .setAutoCancel(true);

                TaskStackBuilder stackBuilder = TaskStackBuilder.create(UpdateServiceManager.this);
                //stackBuilder.addParentStack(SettingsActivity.class);
                stackBuilder.addNextIntent(new Intent(UpdateServiceManager.this, SettingsActivity.class));
                PendingIntent resultPendingIntent =
                        stackBuilder.getPendingIntent(
                                0,
                                PendingIntent.FLAG_UPDATE_CURRENT
                        );
                mNotifyBuilder.setContentIntent(resultPendingIntent);
                mNotificationManager.notify(notifyID, mNotifyBuilder.build()); //Build notification
            }
        }
    }
}


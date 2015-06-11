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
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Timer;
import java.util.TimerTask;

/**
 * Created by Cas on 9-6-2015.
 */
public class UpdateServiceManager extends Service {
    // constant
    public static final long NOTIFY_INTERVAL = 5 * 1000; // 10 seconds

    // run on another Thread to avoid crash
    private Handler mHandler = new Handler();
    // timer handling
    private Timer mTimer = null;

    SharedPreferences pref;
    Boolean notify = true;

    int mesLast = -1;
    String messageTitle = "";
    String messageDesc = "";
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
        pref = PreferenceManager.getDefaultSharedPreferences(ctx);
    }

    class TimeDisplayTimerTask extends TimerTask {

        @Override
        public void run() {
            // run on another thread
            mHandler.post(new Runnable() {

                @Override
                public void run() {
                    notify = pref.getBoolean("pref_notify", true);
                    fineNotify = pref.getString("pref_fine", "I'm fine");
                    tempNotify = pref.getString("pref_temp", "Plant temperature is nawt perf");
                    lightNotify = pref.getString("pref_light", "Plant light is nawt perf");
                    moistNotify = pref.getString("pref_moist", "Plant moist is nawt perf");
                    temp_lightNotify = pref.getString("pref_temp_light", "Plant temperature and light is nawt perf");
                    temp_moistNotify = pref.getString("pref_temp_light", "Plant temperature and moist is nawt perf");
                    light_moistNotify = pref.getString("pref_light_moist", "Plant light and moist is nawt perf");
                    temp_light_moistNotify = pref.getString("pref_temp_light_light", "Plant probably dieded trice");
                    if (notify) {
                        new JsonData(UpdateServiceManager.TimeDisplayTimerTask.this, "http://casnetwork.tk/plant/data.php?notification").execute();
                        /*Toast.makeText(getApplicationContext(), getDateTime(),
                                Toast.LENGTH_SHORT).show();
                        */
                    }
                }

            });
        }

        private String getDateTime() {
            // get date time in custom format
            SimpleDateFormat sdf = new SimpleDateFormat("[yyyy/MM/dd - HH:mm:ss]");
            return sdf.format(new Date());
        }

        public void updateView(JSONObject json) {
            if (json != null) {
                try {
                    JSONArray notifyArray = json.getJSONArray("Notify");
                    if (notifyArray.length() != 0) {
                        for (int i = 0; i < notifyArray.length(); i++) {
                            //code
                            try {
                                lastArray.get(i);
                            } catch (IndexOutOfBoundsException e){
                                lastArray.add(i, 0);
                            }
                            try {
                                lastMessage.get(i);
                            }catch (IndexOutOfBoundsException e){
                                lastMessage.add(i, "");
                            }
                            try {
                                JSONObject c = notifyArray.getJSONObject(i);

                                String name = c.getString("name");
                                int plantID = c.getInt("plant_id");

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
                                    String dateTime;
                                    for (int j = 0; j < typeArray.length(); j++) {
                                        JSONObject type = typeArray.getJSONObject(j);
                                        minTemp = type.getDouble("minTemp");
                                        maxTemp = type.getDouble("maxTemp");
                                        minLight = type.getDouble("minLight");
                                        maxLight = type.getDouble("maxLight");
                                        minMoist = type.getDouble("minMoist");
                                        maxMoist = type.getDouble("maxMoist");
                                    }
                                    for (int j = 0; j < dataArray.length(); j++) {
                                        JSONObject data = dataArray.getJSONObject(j);
                                        temp = data.getDouble("temp");
                                        light = data.getDouble("light");
                                        moist = data.getDouble("moist");
                                        dateTime = data.getString("dateTime");
                                    }

                                    int mes = 0;
                                    if (temp != null && maxTemp != null && minTemp != null) {
                                        if (temp > maxTemp || temp < minTemp) {
                                            //Toast.makeText(getApplicationContext(), name + " Temp not cool m8", Toast.LENGTH_SHORT).show();
                                            mes = 1;
                                        }
                                    }
                                    if (light != null && maxLight != null && minLight != null) {
                                        if (light > maxLight || light < minLight) {
                                            //Toast.makeText(getApplicationContext(), name + " Light not cool m8", Toast.LENGTH_SHORT).show();
                                            if (mes == 1) {
                                                mes = 4;
                                            } else {
                                                mes = 2;
                                            }
                                        }
                                    }
                                    if (moist != null && maxMoist != null && minMoist != null) {
                                        if (moist > maxMoist || moist < minMoist) {
                                            //Toast.makeText(getApplicationContext(), name + " Moist not cool m8", Toast.LENGTH_SHORT).show();
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
                                    if (lastArray.get(i) != mes) {
                                        lastArray.set(i, mes);
                                        String message = "";
                                        switch (lastArray.get(i)) {
                                            case 0:
                                                //No mess
                                                message = fineNotify;
                                                //createNotification(name, "I'm fine thanks u fag. Danx fur asking", plantID);
                                                break;
                                            case 1:
                                                //Temp not ok
                                                message = tempNotify;
                                                //createNotification(name, "Check out mah temp", plantID);
                                                break;
                                            case 2:
                                                //Light not ok
                                                message = lightNotify;
                                                //createNotification(name, "Check out mah light", plantID);
                                                break;
                                            case 3:
                                                //Moist not ok
                                                message = moistNotify;
                                                //createNotification(name, "Check out mah moist", plantID);
                                                break;
                                            case 4:
                                                //Temp & Light not ok
                                                message = temp_lightNotify;
                                                //createNotification(name, "r u gonna let meh die... Temp & Light bruh", plantID);
                                                break;
                                            case 5:
                                                //Temp & Moist not ok
                                                message = temp_moistNotify;
                                                //createNotification(name, "r u gonna let meh die... Temp & Moist bruh", plantID);
                                                break;
                                            case 6:
                                                //Light & Moist not ok
                                                message = light_moistNotify;
                                                //createNotification(name, "r u gonna let meh die... Light & Moist bruh", plantID);
                                                break;
                                            case 7:
                                                //Nothing is ok...
                                                message = temp_light_moistNotify;
                                                //createNotification(name, "I diededed trice", plantID);
                                                break;
                                        }
                                        if(!message.isEmpty()) {
                                            lastMessage.set(i, name + ": " + message);
                                        } else {
                                            lastMessage.set(i, "");
                                        }
                                        createNotification();
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
            if(!cancelNotify){
                NotificationManager notifManager= (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
                notifManager.cancelAll();
            } else {
                NotificationManager mNotificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
                // Sets an ID for the notification, so it can be updated
                int notifyID = 1;
                NotificationCompat.Builder mNotifyBuilder = new NotificationCompat.Builder(UpdateServiceManager.this)
                        .setContentTitle("Weed Plantie")
                        .setContentText("Scroll down for info")
                        .setSmallIcon(R.drawable.notify_icon)
                        .setDefaults(Notification.DEFAULT_ALL)
                        .setPriority(NotificationCompat.PRIORITY_HIGH)
                        .setColor(Color.parseColor("#1565C0"))
                        .setStyle(new NotificationCompat.BigTextStyle()
                                .bigText(bigText))
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
                mNotificationManager.notify(notifyID, mNotifyBuilder.build());
            }
        }

        public void createNotification(String title, String text, int plantID) {
            messageTitle += title + ".\n";
            messageDesc += title + ": " + text + ".\n";
            NotificationManager mNotificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
            // Sets an ID for the notification, so it can be updated
            int notifyID = 1;
            NotificationCompat.Builder mNotifyBuilder = new NotificationCompat.Builder(UpdateServiceManager.this)
                    .setContentTitle("Weed Plantie")
                    .setContentText(messageTitle)
                    .setSmallIcon(R.drawable.notify_icon)
                    .setDefaults(Notification.DEFAULT_ALL)
                    .setPriority(NotificationCompat.PRIORITY_HIGH)
                    .setColor(Color.parseColor("#1565C0"))
                    .setStyle(new NotificationCompat.BigTextStyle()
                            .bigText(messageDesc))
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
            mNotificationManager.notify(notifyID, mNotifyBuilder.build());


            /*NotificationCompat.Builder mBuilder =
                    new NotificationCompat.Builder(UpdateServiceManager.this)
                            .setSmallIcon(icon)
                            .setContentTitle(title)
                            .setContentText(text)
                            .setPriority(NotificationCompat.PRIORITY_HIGH)
                            .setVibrate(new long[0])
                            .setVisibility(1)
                            .setAutoCancel(true)
                            .setOnlyAlertOnce(false)
                            .setColor(Color.parseColor("#1565C0"));
            Intent resultIntent = new Intent(UpdateServiceManager.this, SettingsActivity.class);
            TaskStackBuilder stackBuilder = TaskStackBuilder.create(UpdateServiceManager.this);
            stackBuilder.addParentStack(SettingsActivity.class);
            stackBuilder.addNextIntent(resultIntent);
            PendingIntent resultPendingIntent =
                    stackBuilder.getPendingIntent(
                            0,
                            PendingIntent.FLAG_UPDATE_CURRENT
                    );
            mBuilder.setContentIntent(resultPendingIntent);
            NotificationManager mNotificationManager =
                    (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
            mNotificationManager.notify(plantID, mBuilder.build());*/
        }
    }
}


package com.casnetwork.android;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.app.TaskStackBuilder;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Handler;
import android.os.IBinder;
import android.preference.PreferenceManager;
import android.support.v4.app.NotificationCompat;
import android.util.Log;
import android.widget.Toast;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Timer;
import java.util.TimerTask;

/**
 * Created by Cas on 9-6-2015.
 */
public class UpdateServiceManager extends Service {
    // constant
    public static final long NOTIFY_INTERVAL = 20 * 1000; // 10 seconds

    // run on another Thread to avoid crash
    private Handler mHandler = new Handler();
    // timer handling
    private Timer mTimer = null;

    SharedPreferences pref;
    Boolean notify = true;

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
                    // display toast
                    notify = pref.getBoolean("pref_notify", true);
                    if (notify) {
                        Toast.makeText(getApplicationContext(), getDateTime(),
                                Toast.LENGTH_SHORT).show();
                        NotificationCompat.Builder mBuilder =
                                new NotificationCompat.Builder(UpdateServiceManager.this)
                                        .setSmallIcon(R.drawable.ic_action_search)
                                        .setContentTitle("Plantomatic")
                                        .setContentText("ur plant dieded")
                                        .setPriority(NotificationCompat.PRIORITY_HIGH)
                                        .setVibrate(new long[0])
                                        .setVisibility(1)
                                        .setAutoCancel(true)
                                        .setOnlyAlertOnce(true);
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
                        mNotificationManager.notify(0, mBuilder.build());
                    }
                }

            });
        }

        private String getDateTime() {
            // get date time in custom format
            SimpleDateFormat sdf = new SimpleDateFormat("[yyyy/MM/dd - HH:mm:ss]");
            return sdf.format(new Date());
        }

    }
}


package com.casnetwork.android;

import android.graphics.Color;
import android.graphics.LightingColorFilter;
import android.graphics.drawable.Drawable;
import android.widget.ProgressBar;

/**
 * Created by Cas on 2-6-2015.
 */
public class ProgressBarStatus {

    private ProgressBar pb;
    private int min, max, value;

    public ProgressBarStatus(ProgressBar pb, int min, int max, int value){
        this.pb = pb;
        this.min = min;
        this.max = max;
        this.value = value;
    }

    public void setProgressBar(){
        int rangeMax = (int) (max * 1.5);
        pb.setMax(rangeMax);
        pb.setProgress(value);
        if((value >= max) || (value <= min)){
            Drawable drawable = pb.getProgressDrawable();
            drawable.setColorFilter(new LightingColorFilter(0xFF000000, Color.parseColor("#FF5252")));
        } else {
            Drawable drawable = pb.getProgressDrawable();
            drawable.setColorFilter(new LightingColorFilter(0xFF000000, Color.parseColor("#80D8FF")));
        }
    }
}

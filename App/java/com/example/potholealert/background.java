package com.example.potholealert;


import android.app.AlertDialog;
import android.content.Context;
import android.media.Ringtone;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.CountDownTimer;

import com.google.android.gms.location.LocationCallback;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;

public class background extends AsyncTask <String, Void, String> {

    AlertDialog dialog;
    Context context;
    public background(Context context){
        this.context = context;
    }

    /*public background(LocationCallback locationCallback) {
        this.context = context;
    }*/

    @Override
    protected void onPreExecute() {
        dialog = new AlertDialog.Builder(context).create();
        dialog.setTitle("Alert");
    }

    @Override
    protected void onPostExecute(String s) {
        if(s != "") {
            dialog.setMessage(s);
            dialog.show();

            try {
                Uri notification = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
                Ringtone r = RingtoneManager.getRingtone(context.getApplicationContext(), notification);
                r.play();
            } catch (Exception e) {
                e.printStackTrace();
            }
            new CountDownTimer(5000, 1000) {

                @Override
                public void onTick(long millisUntilFinished) {
                    // TODO Auto-generated method stub

                }

                @Override
                public void onFinish() {
                    // TODO Auto-generated method stub

                    dialog.dismiss();
                }
            }.start();
        }

    }

    @Override
    protected String doInBackground(String... voids){
        String result = "";
        String lat = voids[0];
        String lon = voids[1];

        String conn_str ="http://192.168.43.184/app-connect.php";
        try{
            URL url = new URL(conn_str);
            HttpURLConnection http = (HttpURLConnection) url.openConnection();
            http.setRequestMethod("POST");

            http.setDoInput(true);
            http.setDoOutput(true);

            OutputStream ops = http.getOutputStream();
            BufferedWriter writer = new BufferedWriter(new OutputStreamWriter(ops, "UTF-8"));
            String data = URLEncoder.encode("lat", "UTF-8")+ "=" + URLEncoder.encode(lat, "UTF-8")
                    +"&&" + URLEncoder.encode("lon", "UTF-8")+ "=" + URLEncoder.encode(lon, "UTF-8");
            writer.write(data);
            writer.flush();
            writer.close();
            ops.close();

            InputStream ips = http.getInputStream();
            BufferedReader reader = new BufferedReader(new InputStreamReader(ips, "ISO-8859-1"));
            String line="";
            while ((line = reader.readLine()) != null){

                result += line;
            }
            reader.close();
            ips.close();
            http.disconnect();
            return result;


        } catch (MalformedURLException e) {
            result = e.getMessage();
        } catch (IOException e) {
            result = e.getMessage();
        }
        return result;
    }
}

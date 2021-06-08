package com.example.potholealert;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.location.LocationManager;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.provider.Settings;
import android.util.Log;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationCallback;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationResult;
import com.google.android.gms.location.LocationServices;

import java.io.IOException;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.List;
import java.util.Locale;

import java.sql.Connection;
import java.sql.Statement;
import java.util.Timer;
import java.util.TimerTask;

import static java.lang.String.format;


public class home extends AppCompatActivity {

    // variables

    Button button;
    TextView latitude,longitude, address;
    String msg;

    ConnectionClass connectionClass;
    ProgressDialog progressDialog;

    private FusedLocationProviderClient fusedLocationClient;
    LocationRequest locationRequest;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);

        //FULL SCREEN
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);

        connectionClass = new ConnectionClass();    //connection object
        progressDialog = new ProgressDialog(this);      //dialog object

        //Alerting
        final Handler handler = new Handler();
        final int delay = 10000; // 1000 milliseconds == 1 second

        handler.postDelayed(new Runnable() {
            public void run() {
                /*try {
                    Connection con;

                    try {
                        Class.forName("com.mysql.cj.jdbc.Driver");

                        con = DriverManager.getConnection("jdbc:mysql://localhost:3306/pms","Manoj","123");
                        if (con == null) {
                            msg = "Please Check your Internet connection";
                        } else {
                            String query = "";
                            Statement st = con.createStatement();
                            st.executeUpdate(query);

                            msg = "Successfully executed 2";

                        }*/

                        //conn = DriverManager.getConnection(ConnURL);
                        /*tatement stmt=con.createStatement();
                        ResultSet rs=stmt.executeQuery("select * from potholes");
                        while(rs.next())
                            System.out.println(rs.getInt(1)+"  "+rs.getFloat(2)+"  "+rs.getFloat(3) + " " + rs.getFloat(4));
                        con.close();*/

                        /*msg = "Successfully executed 1";
                    }catch(SQLException se){
                        Log.e("ERRO 1", se.getMessage());
                        msg = "exception 1";
                    }catch (ClassNotFoundException e){
                        Log.e("ERRO 2", e.getMessage());
                        msg = "exception 2";
                    }catch(Exception e){
                        Log.e("ERRO 3", e.getMessage());
                        msg = "exception 3";
                    }

                    /*if (con == null) {
                        msg = "Please Check your Internet connection";
                    } else {
                        String query = "";
                        Statement st = con.createStatement();
                        st.executeUpdate(query);

                        msg = "Successfully executed 2";

                    }*/
                /*}catch (Exception e){
                    msg = "Exception :" + e;
                }*/

                //Toast.makeText(getBaseContext(), ""+msg, Toast.LENGTH_LONG).show();

                /*if(ActivityCompat.checkSelfPermission(home.this, Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED
                        && ActivityCompat.checkSelfPermission(home.this, Manifest.permission.ACCESS_COARSE_LOCATION) == PackageManager.PERMISSION_GRANTED){
                    getCurrentLocation();

                }
                else{
                    //when permission is not given

                    ActivityCompat.requestPermissions(home.this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION, Manifest.permission.ACCESS_COARSE_LOCATION}, 100);
                }*/

                //Toast.makeText(home.this,"Alert!!!",Toast.LENGTH_LONG).show(); // Do your work here
                handler.postDelayed(this, delay);
            }
        }, delay);

        /*new Timer().scheduleAtFixedRate(new TimerTask() {
            @Override
            public void run() {
                Toast.makeText(home.this,"You clicked yes button",Toast.LENGTH_LONG).show();
                /*
                AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(home.this);
                alertDialogBuilder.setMessage("Are you sure, You wanted to make decision");
                        alertDialogBuilder.setPositiveButton("yes",
                                new DialogInterface.OnClickListener() {
                                    @Override
                                    public void onClick(DialogInterface arg0, int arg1) {
                                        Toast.makeText(home.this,"You clicked yes button",Toast.LENGTH_LONG).show();
                                    }
                                });

                alertDialogBuilder.setNegativeButton("No",new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        finish();
                    }
                });

                AlertDialog alertDialog = alertDialogBuilder.create();
                alertDialog.show();
            }
        }, 0, 10000);*/

        button = findViewById(R.id.get_location_button);
        latitude = findViewById(R.id.latitude);
        longitude = findViewById(R.id.longitude);
        address = findViewById(R.id.address);

        fusedLocationClient = LocationServices.getFusedLocationProviderClient(home.this);

        connectionClass = new ConnectionClass();

        progressDialog = new ProgressDialog(this);

        locationRequest = LocationRequest.create()
                .setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY)
                .setInterval(2000)
                .setFastestInterval(1000);

        button.setOnClickListener(view -> {
            if(ActivityCompat.checkSelfPermission(home.this, Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED
                    && ActivityCompat.checkSelfPermission(home.this, Manifest.permission.ACCESS_COARSE_LOCATION) == PackageManager.PERMISSION_GRANTED){
                getCurrentLocation();
            }
            else{
                //when permission is not given

                ActivityCompat.requestPermissions(home.this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION, Manifest.permission.ACCESS_COARSE_LOCATION}, 100);
            }
        });
    }
    @SuppressLint("SetTextI18n")
    LocationCallback locationCallback = new LocationCallback() {

        @Override
        public void onLocationResult(@NonNull LocationResult locationResult) {
            Location location1 = locationResult.getLastLocation();
            latitude.setText(format("Latitude : %s", location1.getLatitude()));
            longitude.setText(format("Longitude : %s", location1.getLongitude()));
            getAddress(location1.getLatitude(), location1.getLongitude());
            String lat = String.valueOf(location1.getLatitude());
            String lon = String.valueOf(location1.getLongitude());
            background bg = new background(home.this);
            bg.execute(lat, lon);
        }};
    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        if(requestCode == 100 && grantResults.length > 0 && (grantResults[0] + grantResults[1] == PackageManager.PERMISSION_GRANTED)){
            getCurrentLocation();
        }
        else{
            Toast.makeText(getApplicationContext(), "Permission denied", Toast.LENGTH_SHORT).show();
        }
    }

    @SuppressLint("MissingPermission")
    private void getCurrentLocation(){
        //location manager
        LocationManager locationManager = (LocationManager) getSystemService(
                Context.LOCATION_SERVICE
        );

        if(locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER) || locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER)){
            fusedLocationClient.getLastLocation().addOnCompleteListener(task -> {
                Location location = task.getResult();

                if(location != null){
                    latitude.setText(String.format("Latitude : %s", location.getLatitude()));
                    longitude.setText(String.format("Longitude : %s", location.getLongitude()));
                    getAddress(location.getLatitude(), location.getLongitude());
                    String lat = String.valueOf(location.getLatitude());
                    String lon = String.valueOf(location.getLongitude());
                    background bg = new background(home.this);
                    bg.execute(lat, lon);
                }
                fusedLocationClient.requestLocationUpdates(locationRequest, locationCallback, Looper.myLooper());
            });
        }
        else{
            //location is not enabled
            startActivity(new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK));
        }
    }

    private void getAddress(double latitude, double longitude){
        Geocoder geocoder = new Geocoder(home.this, Locale.getDefault());
        List<Address> add ;
        try {
            add = geocoder.getFromLocation(latitude, longitude, 1);
            address.setText(String.format("Address : %s", add.get(0).getAddressLine(0)));
            Log.d("test", "add: " + add);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}

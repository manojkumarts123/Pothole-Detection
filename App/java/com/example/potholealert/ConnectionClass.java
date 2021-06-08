package com.example.potholealert;

import android.os.StrictMode;
import android.util.Log;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class ConnectionClass {
    String classs="com.mysql.jdbc.Driver";
    String url = "jdbc:mysql://192.168.43.184/pms";
    String un = "Manoj";
    String password = "123";

    public Connection CONN(){
        //StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        //StrictMode.setThreadPolicy(policy);
        Connection conn = null;
        try {
            Class.forName(classs);
            conn = DriverManager.getConnection(url, un, password);
            //conn = DriverManager.getConnection(ConnURL);
        }catch(SQLException se){
            Log.e("ERRO 1.1", se.getMessage());
        }catch (ClassNotFoundException e){
            Log.e("ERRO 1.2", e.getMessage());
        }catch(Exception e){
            Log.e("ERRO 1.3", e.getMessage());
        }
        return conn;
    }

}

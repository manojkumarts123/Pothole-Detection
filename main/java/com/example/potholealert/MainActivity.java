package com.example.potholealert;

import androidx.appcompat.app.AppCompatActivity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class MainActivity extends AppCompatActivity {
    private static Button start;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        start=(Button) findViewById(R.id.start_button);

        start.setOnClickListener(this::onClick);


    }

    private void onClick(View v) {
        Intent home = new Intent(MainActivity.this, home.class);
        startActivity(home);
    }
}
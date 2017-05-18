package com.javademo;

import com.thirdparty.Third;

public class Fourth {

    public static String output() {
        Third third = new Third();
        String thirdOutput = third.output();
        return thirdOutput + "->" + "Fourth";
    }

    public static void main(String args[]) {

        System.out.println(Fourth.output());
    }
}

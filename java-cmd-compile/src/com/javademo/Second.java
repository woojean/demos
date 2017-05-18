package com.javademo;

public class Second {

    public static String output() {
        First first = new First();
        String firstOutput = first.output();
        return firstOutput + "->" + "Second";
    }

    public static void main(String args[]) {

        System.out.println(Second.output());
    }
}

#!/bin/sh

javac -classpath yuicompressor-2.4.jar:. Main.java
java -classpath yuicompressor-2.4.jar:. Main

cat test.htm test.js test2.htm > regulation.htm

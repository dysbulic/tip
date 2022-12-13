#include "HelloWorld.h"
#include <stdio.h>

JNIEXPORT void JNICALL 
Java_HelloWorld_displayString(JNIEnv *env, jobject this, jstring j_string) {
  const char *str = (*env)->GetStringUTFChars(env, j_string, 0);
  printf("%s\n", str);
  (*env)->ReleaseStringUTFChars(env, j_string, str);
  return;
}

JNIEXPORT jint JNICALL Java_HelloWorld_getInt(JNIEnv *env, jclass this) {
  return 5;
}

JNIEXPORT jstring JNICALL Java_HelloWorld_getString(JNIEnv *env, jclass this) {
  char* returnValue = "Ç String";
  return (*env)->NewStringUTF(env, returnValue);
}

JNIEXPORT jdouble JNICALL Java_HelloWorld_getDouble(JNIEnv *env, jclass this) {
  return 6.3;
}

JNIEXPORT jobject JNICALL Java_HelloWorld_getPoint(JNIEnv *env, jclass this) {
  jclass point_class = (*env)->FindClass(env, "java.awt.Point");
  jobject point = (*env)->AllocObject(env, point_class);
  (*env)->SetIntField(env, point, (*env)->GetFieldID(env, point_class, "x", "I"), 10);
  (*env)->SetIntField(env, point, (*env)->GetFieldID(env, point_class, "y", "I"), -3);
  return point;
}

JNIEXPORT jobjectArray JNICALL Java_HelloWorld_getArray
 (JNIEnv *env, jclass this, jint width, jint height) {
  /* The [I means a two dimensional array of integers */
  jobjectArray j_int_array =
    (*env)->NewObjectArray(env, width, (*env)->FindClass(env, "[I"), NULL);
  /* TODO: make sure the maps are the same size */
  int i, j;
  jint indexes[height];
  for(i = 0; i < width; i++) {
    jintArray j_col = (*env)->NewIntArray(env, height);
    for(j = 0; j < height; j++) {
      indexes[j] = i * height + j;
    }
    (*env)->SetIntArrayRegion(env, j_col, 0, height, indexes);
    (*env)->SetObjectArrayElement(env, j_int_array, i, j_col);
  }
  return j_int_array;
}

JNIEXPORT void JNICALL Java_HelloWorld_randomizeArray
 (JNIEnv *env, jclass this, jobjectArray j_array) {
}

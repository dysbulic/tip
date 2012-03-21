#include <jni.h>
#include <stdio.h>
#include <stdlib.h>

int main(int argc, char *argv[], char **envp) {
  JavaVMOption options[2];
  JavaVMInitArgs vm_args;
  JavaVM *jvm;
  JNIEnv *env;
  long result;
  jmethodID mid;
  jfieldID fid;
  jobject jobj;
  jclass cls;
  int asize;

  options[0].optionString = ".";
  options[1].optionString = "-Djava.compiler=NONE";

  vm_args.version = JNI_VERSION_1_2;
  vm_args.options = options;
  vm_args.nOptions = 2;
  vm_args.ignoreUnrecognized = JNI_FALSE;

  result = JNI_CreateJavaVM(&jvm,(void **)&env, &vm_args);
  if(result == JNI_ERR ) {
    printf("Error invoking the JVM");
    exit (-1);
  }

  cls = (*env)->FindClass(env,"ArrayHandler");
  if(cls == NULL) {
    printf("can't find class ArrayHandler\n");
    exit (-1);
  }
  (*env)->ExceptionClear(env);
  mid = (*env)->GetMethodID(env, cls, "<init>", "()V");
  jobj = (*env)->NewObject(env, cls, mid);
  fid = (*env)->GetFieldID(env, cls, "arraySize", "I");
  asize = (*env)->GetIntField(env, jobj, fid);

  printf("size of array is %d",asize);
  (*jvm)->DestroyJavaVM(jvm);

  return 0;
}

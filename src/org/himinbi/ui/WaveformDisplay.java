/*
 * Copyright (c) 1999, 2000 by Matthias Pfisterer
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * - Redistributions of source code must retain the above copyright notice,
 *   this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright
 *   notice, this list of conditions and the following disclaimer in the
 *   documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 */
package org.himinbi.ui;

import java.io.BufferedInputStream;
import java.io.File;
import java.io.InputStream;
import java.io.IOException;

import java.net.URL;

import java.util.logging.Logger;

import javax.sound.sampled.AudioFormat;
import javax.sound.sampled.AudioInputStream;
import javax.sound.sampled.AudioSystem;
import javax.sound.sampled.DataLine;
import javax.sound.sampled.LineUnavailableException;
import javax.sound.sampled.Mixer;
import javax.sound.sampled.SourceDataLine;



/**
 * Displays a waveform for a sampled audio source
 */
public class WaveformDisplay {
    private static int DEFAULT_EXTERNAL_BUFFER_SIZE = 128000;

    private static Logger logger =
        Logger.getLogger(WaveformDisplay.class.getName());

    public static void main(String[] args) throws Exception {
        String	strMixerName = null;
        int nExternalBufferSize = DEFAULT_EXTERNAL_BUFFER_SIZE;
        int nInternalBufferSize = AudioSystem.NOT_SPECIFIED;

        int sampleSizeInBits = 16;
        boolean isBigEndian = false;

        boolean isFilenameURL = true;
        String filenameURL = null;

        AudioInputStream audioInputStream = null;
        if(isFilenameURL) {
            URL url = new URL(filenameURL);
            audioInputStream = AudioSystem.getAudioInputStream(url);
        } else {
            if(filenameURL.equals("-")) {
                InputStream inputStream = new BufferedInputStream(System.in);
                audioInputStream = AudioSystem.getAudioInputStream(inputStream);
            } else {
                File file = new File(filenameURL);
                audioInputStream = AudioSystem.getAudioInputStream(file);
            }
        }
    
        /*
         *    From the AudioInputStream, i.e. from the sound file,
         *    we fetch information about the format of the
         *    audio data.
         *    These information include the sampling frequency,
         *    the number of
         *    channels and the size of the samples.
         *    These information
         *    are needed to ask Java Sound for a suitable output line
         *    for this audio stream.
         */
        AudioFormat audioFormat = audioInputStream.getFormat();
        DataLine.Info info = new DataLine.Info(SourceDataLine.class,
                                               audioFormat, nInternalBufferSize);
        boolean isSupportedDirectly = AudioSystem.isLineSupported(info);
        if(!isSupportedDirectly) {
            AudioFormat sourceFormat = audioFormat;
            AudioFormat targetFormat = new AudioFormat(AudioFormat.Encoding.PCM_SIGNED,
                                                       sourceFormat.getSampleRate(),
                                                       sampleSizeInBits,
                                                       sourceFormat.getChannels(),
                                                       sourceFormat.getChannels() * (sampleSizeInBits / 8),
                                                       sourceFormat.getSampleRate(),
                                                       isBigEndian);
            audioInputStream = AudioSystem.getAudioInputStream(targetFormat, audioInputStream);
            audioFormat = audioInputStream.getFormat();
        }

        SourceDataLine line = getSourceDataLine(strMixerName, audioFormat, nInternalBufferSize);
        if(line == null) {
            logger.fine("AudioPlayer: cannot get SourceDataLine for format " + audioFormat);
            System.exit(1);
        }
        logger.fine("AudioPlayer.main(): line: " + line);
        logger.fine("AudioPlayer.main(): line format: " + line.getFormat());
        logger.fine("AudioPlayer.main(): line buffer size: " + line.getBufferSize());

        // enable output
        line.start();

        int nBytesRead = 0;
        byte[] abData = new byte[nExternalBufferSize];
        logger.fine("AudioPlayer.main(): starting main loop");
        do {
            try {
                nBytesRead = audioInputStream.read(abData, 0, abData.length);
            } catch (IOException e) {
                e.printStackTrace();
            }
            logger.finer("AudioPlayer.main(): read from AudioInputStream (bytes): " + nBytesRead);
            if(nBytesRead >= 0) {
                int nBytesWritten = line.write(abData, 0, nBytesRead);
                logger.finer("AudioPlayer.main(): written to SourceDataLine (bytes): " + nBytesWritten);
            }
        } while(nBytesRead != -1);

        logger.fine("AudioPlayer.main(): finished main loop");

        logger.fine("AudioPlayer.main(): before drain");
        line.drain();

        logger.fine("AudioPlayer.main(): before close");
        line.close();
    }

    private static SourceDataLine getSourceDataLine(String strMixerName,
                                                    AudioFormat audioFormat,
                                                    int nBufferSize) {
        SourceDataLine line = null;
        DataLine.Info info = new DataLine.Info(SourceDataLine.class,
                                               audioFormat, nBufferSize);
        try {
            if(strMixerName != null) {
                Mixer.Info mixerInfo = getMixerInfo(strMixerName);
                if(mixerInfo == null) {
                    logger.severe("AudioPlayer: mixer not found: " + strMixerName);
                    System.exit(1);
                }
                Mixer mixer = AudioSystem.getMixer(mixerInfo);
                line = (SourceDataLine) mixer.getLine(info);
            } else {
                line = (SourceDataLine) AudioSystem.getLine(info);
            }

            line.open(audioFormat, nBufferSize);
        } catch (LineUnavailableException e) {
            e.printStackTrace();
        } catch (Exception e) {
            e.printStackTrace();
        }
        return line;
    }

    public static Mixer.Info getMixerInfo(String mixerName) {
        Mixer.Info[] mixerInfos = AudioSystem.getMixerInfo();
        for(int i = 0; i < mixerInfos.length; i++) {
            if(mixerInfos[i].getName().equals(mixerName)) {
                return mixerInfos[i];
            }
        }
        return null;
    }
}

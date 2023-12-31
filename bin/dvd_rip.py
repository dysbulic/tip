#!/usr/bin/python

"""
Author: dysbulic <dys@dhappy.org>
Date: 2006/07/07
Usage:
  Rips a DVD to Matroska preserving as much of the original content as
  possible. Preserved information includes:
    * All tracks
    * All audio preserving the number of channels
    * Subtitles
Dependencies:
  Several unix utilities are accessed by this program:
    * lsdvd - reads information about the DVD file structure
    * tccat/tcextract - from transcode - extracts subtitle information from the DVD
    * mencoder - extracts and converts DVD tracks
    * subtitle2pgm - from subrib (or subtitleripper) - converts vobsub to pgm
    * pgm2txt - from gocr - converts subtitles to text
    * faac - encodes audio tracks as faac
 ToDo:
   Add GUI to choose the proper subtitle grayscale conversion
   Add GUI to choose cropping parameters
"""

import os, re, glob
from stat import *
import sys, getopt
import time
import string
import subprocess, signal
import xml.dom.minidom
from xml.dom.minidom import Node
import StringIO

try:
    optList, args = getopt.getopt(sys.argv[1:], "q", ["quiet", "overwrite", "grayscale=",
                                                      "spellcheck", "nocrop", "cropsamples=",
                                                      "filter=", "maxframes=", "dvd-image=",
                                                      "extract-vobs", "audio-type=", "dry-run",
                                                      "destination-dir=", "rip-only",
                                                      "skip-subtitles", "skip-audio"])
except getopt.GetoptError:
    print __doc__
    print """  --overwrite removes existing files, otherwise, only missing files are generated
  --nocrop disables autocropping
  --filter specifies the x264 filter parameter [0:0]
  --maxframes [number] specifies the number of frames to encode [all frames]
  --dvd-image [directory/iso image] specifies an iso image to use as the source [dvd://]
  --extract-vobs rip the streams to the hard drive allowing dvd removal
  --rip-only only rip the vobs, don't encode
  --destination-dir [directory] the directory to rip to
  --audio-type [ogg/aac]
  --skip-subtitles to not extract subtitle information

  If a dvd was previously ripped using --rip-only, to encode the --destinaiton-dir must be
  provided to specify where the vobs are."""
    sys.exit(2)

opts = {}
for opt, arg in optList:
    opts[opt] = arg

if opts.has_key("--grayscale"):
    subGrayscale = opts["--grayscale"]
else:
    subGrayscale = "0,255,255,255"
    subGrayscale = "all"

if opts.has_key("--cropsamples"):
    cropsamples = int(opts["--cropsamples"])
else:
    cropsamples = 500

if opts.has_key("--audio-type"):
    audioType = opts["--audio-type"]
else:
    audioType = "m4a"

destinationDir = ""
if opts.has_key("--destination-dir"):
    destinationDir = opts["--destination-dir"]

if opts.has_key("--dvd-image"):
    dvdImage = opts["--dvd-image"]
    if not dvdImage.startswith('/'):
        dvdImage = os.getcwd() + '/' + dvdImage
else:
    dvdImage = "/dev/dvd"

if opts.has_key("--maxframes"):
    mplayerFrames = "-frames %s " % opts["--maxframes"]
    transcodeFrames = "-c %s " % opts["--maxframes"]
else:
    mplayerFrames = transcodeFrames = ""

if opts.has_key("--filer"):
    x264Filter = opts["--filter"]
else:
    x264Filter = "0:0"

if opts.has_key("--rip-only"):
    opts["--extract-vobs"] = True

if opts.has_key("--filer"):
    langorder = split(opts["--filter"], ",")
else:
    langorder = ["en", "fr", "es"]


x264args = {"subme" : "7", "subq" : "6", "analyse" : "all", "frameref" : "2",
            "qp" : "23", "qcomp" : "1", "threads" : "auto",
            "min-keyint" : "12", "keyint" : "500", "bime" : None, "direct" : "auto",
            "ref" : "16", "mixed-refs" : None, # "filter" : x264Filter,
            "bframes" : "5", "b-pyramid" : None, "weightb" : None, "b-rdo" : None,
            "no-fast-pskip" : None, "trellis" : "2", "cqm" : "jvt",
            "partitions" : "all", "8x8dct" : None, "me" : "umh"}

# Options to override or add on a particular pass to speed the first
# pass while maintaining quality on the second
passX264args = [{}, # seeing artifacts {"subq" : "1", "frameref" : "1", "me" : "dia", "trellis" : "0"},
                {"partitions" : "all", "8x8dct" : None, "me" : "umh"}]

gocrArgs = {}

useMEncoder = True

mencoderAudioParam = "-oac lavc -lavcopts acodec=mp3"
mencoderAudioParam = "-oac faac"
mencoderAudioParam = "-oac copy"

# Autocropping output
cropRE = re.compile("\[CROP\].*-vf crop=([^\)]+)")

# Mplayer identify fps
fpsRE = re.compile(".*?([\d/]+)\s*fps progressive NTSC content detected.*")

# Mplayer identify audio ids
audioIDRE = re.compile("ID_AUDIO_ID=([^\n]+)")

# Number of seconds to run video through mplayer to identify telecine
verifySeconds = 30

#if dvdImage is "/dev/dvd":
#    mountDir = dvdImage
#else:
#    mountDir = "dvdrip"
#    if not os.path.exists(mountDir):
#        os.mkdir(mountDir)
#    command = 'mount | grep "%s"' % dvdImage
#    returnCode = os.system(command)
#    if returnCode is not 0:
#        command = "sudo mount -o loop '%s' '%s'" % (dvdImage, mountDir)
#        print "Mounting Image: %s" % command
#        returnCode = os.system(command)

lsDVDListing = "lsdvd_listing.xml"

directoryDepth = 0
lsdvdout = None
dvdDoc = None

destinationDir = destinationDir.rstrip("/")

if destinationDir == "" or not os.path.exists("%s/%s" % (destinationDir, lsDVDListing)):
    # python output is broken, so use XML
    command = "lsdvd -Ox -x '%s'" % (dvdImage)
    print "Reading DVD Structure: %s" % command
    (fdin, fdout) = os.popen2(command, "r")
    lsdvdout = fdout.read()
    fdin.close()
    fdout.close()
    lsdvdout = re.sub("&", "&amp;", lsdvdout)
    lsdvdBuffer = StringIO.StringIO(lsdvdout)
    dvdDoc = xml.dom.minidom.parse(lsdvdBuffer)
elif os.path.exists("%s/%s" % (destinationDir, lsDVDListing)):
    print "     Loading: [%s] '%s/%s'" % (time.strftime("%H:%M:%S"), destinationDir, lsDVDListing)
    dvdDoc = xml.dom.minidom.parse("%s/%s" % (destinationDir, lsDVDListing))

if dvdDoc is None:
    print "  Error: [%s] Couldn't find dvd listing" % time.strftime("%H:%M:%S")
    sys.exit(-1)

discTitle = dvdDoc.getElementsByTagName("title")[0].childNodes[0].data
if (discTitle == "unknown" or discTitle == "") or destinationDir != "":
    if destinationDir.endswith(" Rip"):
        discTitle = destinationDir[:-4]
    else:
        discTitle = destinationDir

discTitle = string.capwords(re.sub("_", " ", discTitle))
discTitle = re.sub(" Of ", " of ", discTitle)
discTitle = re.sub(" The ", " the ", discTitle)
if destinationDir == "":
    destinationDir =  discTitle + " Rip"

print "      Titles: [%s] Ripping '%s' to '%s'" % (time.strftime("%H:%M:%S"), discTitle, destinationDir)

if not os.path.exists(destinationDir) and not opts.has_key("--dry-run"):
    os.mkdir(destinationDir)
if os.path.exists(destinationDir):
    os.chdir(destinationDir)
    directoryDepth += 1

if not os.path.exists(lsDVDListing) and not opts.has_key("--dry-run"):
    print "      Layout: [%s] Saving DVD Layout" % (time.strftime("%H:%M:%S"))
    outFile = open(lsDVDListing, 'w')
    outFile.write(lsdvdout)
    outFile.close()

device = dvdDoc.getElementsByTagName("device")[0].childNodes[0].data

returnCode = 0 # in case this is a dry-run

dvdRipFile = "dvd_rip.vob"

logFile = "video.x264.log"
outPattern = "video.pass %s.h264.%s"
if not useMEncoder:
    outputExtension = "mkv"
else:
    outputExtension = "avi"
encodingPasses = 2

finalOutput = outPattern % (encodingPasses, outputExtension)

sources = {} # 
for track in dvdDoc.getElementsByTagName("track"):
    length = float(track.getElementsByTagName("length")[0].childNodes[0].data)
    trackId = int(track.getElementsByTagName("ix")[0].childNodes[0].data)
    if length < 1:
        print "    Skipping: [%s] Track %d: Length: %s" % (time.strftime("%H:%M:%S"), trackId, length)
        continue
    outDir = "title %s" % (trackId)
    if opts.has_key("--extract-vobs"):
        if not os.path.exists(outDir) and not opts.has_key("--dry-run"):
            os.mkdir(outDir)
        if not os.path.exists('%s/%s' % (outDir, dvdRipFile)):
            command = "tccat -i %s -T %s,-1 > '%s/%s'" % (dvdImage, trackId, outDir, dvdRipFile)
            if sources.has_key(trackId) and os.path.exists("%s/%s" % (outDir, sources[trackId])):
                print "    Skipping: [%s] %s" % (time.strftime("%H:%M:%S"), command)
            else:
                print "     Ripping: [%s] %s" % (time.strftime("%H:%M:%S"), command)
                if not opts.has_key("--dry-run"):
                    returnCode = os.system(command)
                if returnCode is not 0:
                    sys.exit(returnCode)
    if os.path.exists("%s/%s" % (outDir, dvdRipFile)):
        sources[trackId] = dvdRipFile
    else:
        sources[trackId] = "-dvd-device '%s' dvd://%s" % (dvdImage, trackId)

if opts.has_key("--rip-only"):
    print "   Rip Done: [%s] %s" % (time.strftime("%H:%M:%S"), destinationDir)
    sys.exit(0)

# Begin by extracting the audio data

for track in dvdDoc.getElementsByTagName("track"):
    trackId = int(track.getElementsByTagName("ix")[0].childNodes[0].data)
    if trackId not in sources:
        continue

    finalFile = "%s -- Title %d.mkv" % (discTitle, trackId)
    if os.path.exists(finalFile):
        print " Skipping: [%s] Processed %s" % (time.strftime("%H:%M:%S"), finalFile)
        continue
    
    fps = track.getElementsByTagName("fps")[0].childNodes[0].data

    outDir = "title %s" % (trackId)

    print "<>Extracting: [%s] Track %s to %s/%s" % (time.strftime("%H:%M:%S"), trackId, destinationDir, outDir)
    if not os.path.exists(outDir) and not opts.has_key("--dry-run"):
        os.mkdir(outDir)
    if os.path.exists(outDir):
        os.chdir(outDir)
        directoryDepth += 1

    # extract subtitles

    if not opts.has_key("--skip-subtitles"):
        if subGrayscale == "all":
            subGrayscale = ["0,255,255,255", "255,0,255,255", "255,255,0,255", "255,255,255,0"]
        else:
            subGrayscale = [subGrayscale]
        for sub in track.getElementsByTagName("subp"):
            vobSubId = sub.getElementsByTagName("streamid")[0].childNodes[0].data
            langId = sub.getElementsByTagName("langcode")[0].childNodes[0].data
            for scale in subGrayscale:
                subTextFile = "%s - subtitle.%s.%s.srt" % (vobSubId, scale, langId)
                if os.path.exists(subTextFile):
                    print "    Skipping: [%s] %s" % (time.strftime("%H:%M:%S"), subTextFile)
                else:
                    outDir = "%s - subtitle.%s" % (vobSubId, langId)
                    if not os.path.exists(outDir) and not opts.has_key("--dry-run"):
                        os.mkdir(outDir)
                    if os.path.exists(outDir):
                        os.chdir(outDir)
                        directoryDepth += 1
                    subFile = "subtitle.%s." % scale
                    if os.path.exists("%s0001.pgm" % subFile):
                        print "    Skipping: [%s] Subtitles: %s/%s0001.pgm exists" % (time.strftime("%H:%M:%S"), outDir, subFile)
                    else:
                        if sources[trackId].endswith(".vob"):
                            command = "cat ../%s" % sources[trackId]
                        else:
                            command = "tccat -i %s -T %s -L" % (dvdImage, trackId)
                        command += " | tcextract -x ps1 -t vob -a %s" % vobSubId
                        command += (" | subtitle2pgm -o '%s' -c %s" % (subFile, scale))
                        print "   Subtitles: [%s] %s" % (time.strftime("%H:%M:%S"), command)
                        if not opts.has_key("--dry-run"):
                            subRip = subprocess.Popen(command, shell=True, stdin=None,
                                                      stdout=None, stderr=subprocess.PIPE)
                            returnCode = subRip.wait()
                            if returnCode != 0:
                                print subRip.stderr.read()
                    
                        if os.path.exists("%s.srtx" % subFile):
                            os.rename("%s.srtx" % subFile, "%ssrtx" % subFile)
    
                    # the pgm2txt script is interactive and needs to be replicated
                    if os.path.exists("%s0001.pgm.txt" % subFile) or True:
                        #print "    Skipping: [%s] OCR: %s/%s0001.pgm.txt exists" % (time.strftime("%H:%M:%S"), outDir, subFile)
                        print "    Skipping: [%s] OCR Conversion: Requires Interaction" % (time.strftime("%H:%M:%S"))
                    else:
                        command = "pgm2txt -f %s '%s'" % (langId, subFile)
                        print "         OCR: [%s] %s" % (time.strftime("%H:%M:%S"), command)
                        if not opts.has_key("--dry-run"):
                            returnCode = os.system(command)
    
                    if not opts.has_key("--dry-run"):
                        if not os.path.exists("%s0001.pgm.txt" % subFile):
                            print "    Skipping: [%s] Combination: %s/%s0001.pgm.txt does not exist" % (time.strftime("%H:%M:%S"), outDir, subFile)
                        else:
                            command = "srttool -s -w -i '%ssrtx' -o '%ssrt'" % (subFile, subFile)
                            print " Recombining: [%s] %s" % (time.strftime("%H:%M:%S"), command)
                            combine = subprocess.Popen(command, shell=True, stdin=None,
                            stdout=None, stderr=subprocess.PIPE)
                            
                            returnCode = combine.wait()
                            if returnCode is not 0:
                                print combine.stderr.read()
                            else:
                                if os.stat(subFile + "srt")[ST_SIZE] == 0:
                                    print "    Skipping: [%s] %s/%ssrt is empty" % (time.strftime("%H:%M:%S"), outDir, subFile)
                                else:
                                    print "      Moving: [%s] %s/%ssrt -> %s" % (time.strftime("%H:%M:%S"), outDir, subFile, subTextFile)
                                    os.rename("%ssrt" % subFile, "../%s" % subTextFile)
                        
                    if directoryDepth > 2:
                        os.chdir("..")
                        directoryDepth -= 1
    
        if opts.has_key("--spellcheck") and os.path.exists(subTextFile):
            command = "emacs '%s'" % subTextFile
            print " Subtitle Edit: [%s] %s" % (time.strftime("%H:%M:%S"), command)
            if not opts.has_key("--dry-run"):
                emacs = subprocess.Popen(command, shell=True)

    # extract audio

    if not opts.has_key("--skip-audio"):
        command = "mplayer -msglevel all=0:identify=4 -frames 0 %s" % sources[trackId]
        print "   Verifying: [%s] Extracting Ids: %s" % (time.strftime("%H:%M:%S"), command)
        audioDetect = subprocess.Popen(command, shell=True, stdin=None,
                                       stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        mplayerAudioIds = [];
        for line in audioDetect.stdout:
            match = audioIDRE.match(line)
            if match is not None:
                mplayerAudioIds.append(int(match.group(1)))

        pcmOpts = ""
        if audioType is "ogg":
            audioFIFO = "audio.fifo.wav"
        else:
            pcmOpts = "-ao pcm:nowaveheader "
            audioFIFO = "audio.fifo.pcm"
        if not os.path.exists(audioFIFO) and not opts.has_key("--dry-run"):
            os.mkfifo(audioFIFO)
        for audio in track.getElementsByTagName("audio"):
            streamid = audio.getElementsByTagName("streamid")[0].childNodes[0].data
            if int(streamid, 0) not in mplayerAudioIds:
                print("       Error: [%s] Audio ID not detected by Mplayer %s [%s] (%s)" %
                      (time.strftime("%H:%M:%S"), streamid, int(streamid, 0), mplayerAudioIds))
                continue

            channelCount = int(audio.getElementsByTagName("channels")[0].childNodes[0].data)
            title = ("%s - %s channel %s.%s" %
                     (streamid, channelCount,
                      audio.getElementsByTagName("format")[0].childNodes[0].data,
                      audio.getElementsByTagName("langcode")[0].childNodes[0].data))
            command = ("mplayer %s%s-aid %s -ao pcm -ao pcm:file='%s' -channels %s -vc null -vo null -msglevel all=0 %s" %
                       (mplayerFrames, pcmOpts, streamid, audioFIFO, channelCount, sources[trackId]))
            outFile = "%s.%s" % (title, audioType)
            if os.path.exists(outFile):
                print "    Skipping: [%s] %s" % (time.strftime("%H:%M:%S"), outFile)
            else:
                print "  Extracting: [%s] %s" % (time.strftime("%H:%M:%S"), command)
                if not opts.has_key("--dry-run"):
                    audioDump = subprocess.Popen(command, shell=True, stdin=None,
                                                 stdout=subprocess.PIPE, stderr=subprocess.PIPE)
                if audioType is "ogg":
                    command = "oggenc --output='%s' '%s'" % (outFile, audioFIFO)
                elif audioType is "m4a":
                    command = ("mencoder %s-aid %s -channels %s -ovc frameno -oac faac -o '%s' %s" %
                               (mplayerFrames, streamid, channelCount, outFile, sources[trackId]))
                    channelOpts = ""
                    if channelCount is 6:
                        channelOpts = " -I 5,6"
                    command = ("faac -P%s -R 48000 -C %s -X --title '%s -- %s' -o '%s' -w '%s'" %
                               (channelOpts, channelCount, discTitle, title, outFile, audioFIFO))
                else:
                    print "Unknown --audio-type: %s" % audioType
                print "    Encoding: [%s] %s" % (time.strftime("%H:%M:%S"), command)
                if not opts.has_key("--dry-run"):
                    returnCode = os.system(command)
                if returnCode is not 0:
                    sys.exit(returnCode)
        if not opts.has_key("--dry-run"):
            os.unlink(audioFIFO)

    if os.path.exists(finalOutput):
        print "    Skipping: [%s] Video Encoding: File Exists: %s" % (time.strftime("%H:%M:%S"), finalOutput)
    else:
        videoFIFO = "video.fifo.y4m"
        if not os.path.exists(videoFIFO) and not opts.has_key("--dry-run"):
            os.mkfifo(videoFIFO)

        # ToDo: Handle the situation where the first pass was done with
        # --nocrop, the program was killed and rerun during the second
        # pass.
        cropParam = ""
        if opts.has_key("nocrop") or os.path.exists(finalOutput):
            print "    Skipping: [%s] No Autocropping" % (time.strftime("%H:%M:%S"))
        else:
            command = "mplayer -vf cropdetect -quiet -vo null -ao null %s" % (sources[trackId])
            print "   Analyzing: [%s] %s" % (time.strftime("%H:%M:%S"), command)
            cropDetect = subprocess.Popen(command, shell=True, stdin=None,
                                          stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            cropParams = {};
            for line in cropDetect.stdout:
                match = cropRE.match(line)
                if match is not None:
                    if not cropParams.has_key(match.group(1)):
                        cropParams[match.group(1)] = 0
                    cropParams[match.group(1)] = cropParams[match.group(1)] + 1
                    if cropParams[match.group(1)] >= cropsamples:
                        break
            os.kill(cropDetect.pid, signal.SIGTERM)
            cropDetect.wait()
            times = [[v[1],v[0]] for v in cropParams.items()]
            times.sort()
            times.reverse()

            if len(times) > 1:
                cropParam = "-vf crop=%s" % times[0][1]
                print "    Cropping: [%s] %s" % (time.strftime("%H:%M:%S"), cropParam)
                cropParam = cropParam + " "
            else:
                print "  Crop Error: No cropping info found"

            for (frequency, param) in times:
                if frequency > 1:
                    print "%10s: %s" % (frequency, param)

        videoDumpCommand = ("transcode %s-y yuv4mpeg,null -x dvd,null -i /dev/dvd -T %s,-1 -f 24,1 -o %s" %
                            (transcodeFrames, trackId, videoFIFO))
        videoDumpCommand = ("mplayer -vo yuv4mpeg:file='%s' %s-noframedrop -nosound -msglevel all=0 %s%s" %
                            (videoFIFO, mplayerFrames, cropParam, sources[trackId]))

        # Even though the track id says the video is NTSC, it is often
        # progressive and this doesn't come up until the video is played.
        # Using the correct framerate however reduces the file size by 60%
        command = ("mplayer -frames %d -vo null -ao null -msglevel all=4 %s" %
                   (round(float(fps) * verifySeconds, 0), sources[trackId]))
        print "   Verifying: [%s] Framerate: %s" % (time.strftime("%H:%M:%S"), command)
        fpsDetect = subprocess.Popen(command, shell=True, stdin=None,
                                     stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        for line in fpsDetect.stdout:
            match = fpsRE.match(line)
            if match is not None:
                fps = match.group(1)
                print "  Correcting: [%s] Framerate: %s" % (time.strftime("%H:%M:%S"), fps)
                break
            
        for encodingPass in xrange(1, encodingPasses + 1):
            if encodingPass == 1 and os.path.exists(logFile):
                print "    Skipping: [%s] Video Encoding Pass #1 (%s)" % (time.strftime("%H:%M:%S"), logFile)
            else:
                outFile = outPattern % (encodingPass, outputExtension)
                if os.path.exists(outFile):
                    print ("    Skipping: [%s] Video Encoding Pass #%s (%s)" %
                           (time.strftime("%H:%M:%S"), encodingPass, outFile))
                else:
                    x264argString = ""
                    args = x264args.copy()
                    args.update(passX264args[encodingPass - 1])
                    if useMEncoder:
                        val = lambda val: (val is None and [""] or ["=" + val])[0]
                        x264argString = ":".join([k + val(v) for k, v in args.items()])
                    else:
                        val = lambda val: (val is None and [""] or [" " + val])[0]
                        x264argString = "--" + " --".join([k + val(v) for k, v in args.items()])

                    if not useMEncoder:
                        print "  Extracting: [%s] %s" % (time.strftime("%H:%M:%S"), videoDumpCommand)
                        if not opts.has_key("--dry-run"):
                            videoDump = subprocess.Popen(videoDumpCommand, shell=True, stdin=None,
                                                         stdout=subprocess.PIPE, stderr=subprocess.PIPE)
                        command = ("x264 %s -o '%s' --pass %s --stats '%s' '%s'" %
                                   (x264argString, outFile, encodingPass, logFile, videoFIFO))
                    else:
                        # removing -noskip -mc 0
                        command = ("mencoder -ovc x264 -x264encopts '%s:pass=%s:fps=%s' -passlogfile '%s' %s -o '%s' -ofps %s %s%s" %
                                   (x264argString, encodingPass, fps, logFile, mencoderAudioParam, outFile, fps, cropParam, sources[trackId]))
                            
                    print "    Encoding: [%s] %s" % (time.strftime("%H:%M:%S"), command)
                    if not opts.has_key("--dry-run"):
                        if not useMEncoder:
                            returnCode = os.system(command)
                        else:
                            returnCode = os.system(command)
                            #encode = subprocess.Popen(command, shell=True, stdin=None,
                            #                          stdout=subprocess.PIPE, stderr=subprocess.PIPE)
                            #for line in encode.stdout:
                            #    print line.rstrip()
                            
                        if returnCode is not 0:
                            sys.exit(returnCode)
    
    # Finally merge everything together. This is done from the files present because audio tracks 
    # and subtitles may have been added by hand.

    subtitleRE = re.compile("subtitle\.(..)\.srt")
    audioRE = re.compile("\.(..)\.m4a")

    # this should also check if anything has been changed
    if os.path.exists("../" + finalFile):
        print "    Skipping: %s" % finalFile
    else:
        tracks = {}
        langid = 0
        command = "mkvmerge -o '../%s'" % finalFile
        command += " -A '%s'" % finalOutput
        for file in glob.glob("*subtitle*.srt"):
            subMatch = subtitleRE.search(file)
            if subMatch is not None:
                command += " --language %s:%s" % (0, subMatch.group(1))
            command += " '%s'" % file
        for file in glob.glob("*.m4a"):
            audioMatch = audioRE.search(file)
            if audioMatch is not None:
                lang = audioMatch.group(1)
                if lang not in tracks:
                    tracks[lang] = langid
                command += " --language %s:%s" % (langid, audioMatch.group(1))
                langid += 1
            command += " '%s'" % file
        for lang in langorder:
            if lang in tracks:
                command += " --default-language %s" % lang
                break
        print "   Combining: [%s] %s" % (time.strftime("%H:%M:%S"), command)
        if not opts.has_key("--dry-run"):
            returnCode = os.system(command)
        if returnCode is not 0:
            print "      FAILED: [%s] %s" % (time.strftime("%H:%M:%S"), command)
            sys.exit(returnCode)


    if directoryDepth > 0:
        os.chdir("..")
    
# These are the commands I used to rip a DVD and encode it to Matroska

# [ -e "$vob" ] || mplayer dvd://1 -dumpstream -dumpfile "$vob"

# original options -- pass 1
#OPTS="subq=4:bframes=4:b_pyramid:weight_b:psnr:bitrate=1500:turbo=1"

# original options -- pass 2
#OPTS="subq=5:partitions=4x4:8x8dct:frameref=3:me=hex:bframes=4:psnr:bitrate=1500"

#OPTS="subme=7:analyse=all:me=umh:8x8dct:qp=23:qcomp=1:min-keyint=12:keyint=500:bime"
#OPTS="$OPTS:direct=auto:ref=16:mixed-refs:bframes=5:b-pyramid:weightb:b-rdo:no-fast-pskip"
#OPTS="$OPTS:trellis=2:cqm=jvt"

#[ -e divx2pass.log ] || mencoder -v \
#         "$vob" \
#        -alang en \
#        -vf crop=720:352:0:64,scale=752:320 \
#        -ovc x264 -x264encopts pass=1:$OPTS \
#        -oac copy \
#        -ofps 24000/1001 \
#        -vobsubout subtitles -vobsuboutindex 0 -slang en \
#        -o /dev/null
#[ -e "$output" ] || mencoder -v \
#         "$vob" \
#        -alang en \
#        -vf crop=720:352:0:64,spp,scale,hqdn3d=2:1:2 \
#        -ovc x264 -x264encopts pass=2:$OPTS \
#        -oac faac -faacopts object=1:tns:quality=100 \
#        -ofps 24000/1001 \
#        -o "$output"
#[ -e "$outsound" ] || mplayer -aid 128 \
#        -ao pcm -ao pcm:file='title1.wav' \
#        -channels 2 -vc null -vo null \
#        -msglevel all=0 "$vob"
#faac -o 0x80\ -\ 6\ channel\ ac3.en.m4a "$outsound"
#mkvmerge -o title1.mkv -A title1.avi 0x80\ -\ 6\ channel\ ac3.en.m4a

#merger subs with jubler

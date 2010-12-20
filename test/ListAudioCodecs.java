import javax.sound.sampled.AudioFileFormat;
import javax.sound.sampled.AudioSystem;
import javax.sound.sampled.Mixer;

public class ListAudioCodecs {
    public static void main(String[] args) {
        System.out.println("Supported target types:");
        AudioFileFormat.Type[] audioTypes = AudioSystem.getAudioFileTypes();
        for(int i = 0; i < audioTypes.length; i++) {
            System.out.println(" " + audioTypes[i].getExtension());
        }

        System.out.println("Available Mixers:");
        Mixer.Info[] mixerInfos = AudioSystem.getMixerInfo();
        for(int i = 0; i < mixerInfos.length; i++) {
            System.out.println(" " + mixerInfos[i].getName() + " : " + mixerInfos[i].getDescription());
        }
        if(mixerInfos.length == 0) {
            System.out.println(" None available");
        }
    }
}

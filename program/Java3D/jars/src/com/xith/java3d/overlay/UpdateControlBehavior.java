package com.xith.java3d.overlay;

import com.xith.java3d.overlay.*;
import javax.media.j3d.*;
import java.util.Enumeration;

public class UpdateControlBehavior extends Behavior 
    implements UpdateManager {
    int UPDATE_ID = 1;
    WakeupOnBehaviorPost wakeup = new WakeupOnBehaviorPost(this, UPDATE_ID);
    UpdatableEntity entity;
    boolean updating = true;
    boolean droppedUpdate = false;

    public UpdateControlBehavior( UpdatableEntity entity ) {
	this.entity = entity;
    }

    public void initialize() {
	wakeupOn(new WakeupOnActivation());
    }

    public boolean isUpdating() {
        return updating;
    }

    public void setUpdating(boolean updating) {
        if (this.updating != updating) {
            this.updating = updating;
            if(updating && droppedUpdate) {
		updateRequested();
            }
        }
    }
    		
    public void processStimulus(Enumeration enumeration) {
	if (updating) {
	    entity.update();
	} else {
	    droppedUpdate = true;
	}
	wakeupOn(wakeup);
    }

    public void updateRequested() {
	postId(UPDATE_ID);
    }
}

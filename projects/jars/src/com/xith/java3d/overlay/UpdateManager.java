package com.xith.java3d.overlay;

public interface UpdateManager {
    public void updateRequested();
    public boolean isUpdating();
    public void setUpdating(boolean updating);
}

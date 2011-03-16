package com.miltec.mvis;

import java.awt.*;
import java.util.List;
import java.util.Iterator;
import java.util.ArrayList;
import java.net.URL;
import java.net.MalformedURLException;
import javax.media.j3d.*;
import javax.vecmath.*;
import com.sun.j3d.utils.image.TextureLoader;
import com.sun.j3d.utils.geometry.*;

public class SimpleScene {
    BranchGroup head;
    TransformGroup platformRotation;
    TransformGroup sceneRoot;
    final static float defaultEarthRadius = 6378137 / 1000f;
    float earthRadius = 100;
    Locale locale;
    String mapname = "images/earth_cl.jpg";

    public SimpleScene(Component observer) {
	this(observer, defaultEarthRadius);
    }

    public SimpleScene(Component observer, float earthRadius) {
	VirtualUniverse universe = new VirtualUniverse();
	locale = new Locale(universe);
	head = new BranchGroup();

	BoundingSphere bounds = new BoundingSphere(new Point3d(), Double.MAX_VALUE);

	Background background = new Background(new Color3f(29f / 255f, 43f / 255f, 153f / 255f));
	background.setApplicationBounds(bounds);
	head.addChild(background);

	DirectionalLight light = new DirectionalLight
	    (new Color3f(100f / 255f, 100f / 255f, 100f / 255f), 
	     new Vector3f(0f, 0f, -1f));
	light.setInfluencingBounds(bounds);
	head.addChild(light);

	if(earthRadius > 0) {
	    Appearance appearance = new Appearance();

            //List<URL> urls = new ArrayList<URL>();
            List urls = new ArrayList();
            URL baseURL = this.getClass().getResource("");
            try {
                urls.add(new URL(baseURL, mapname));
            } catch(MalformedURLException e) {}
            try {
                urls.add(new URL(baseURL, "../../../" + mapname));
            } catch(MalformedURLException e) {}
            try {
                urls.add(new URL("file:" + mapname));
            } catch(MalformedURLException e) {}

            TextureLoader texture = null;
            URL imageURL = null;

            //for(URL url : urls) {
            for(Iterator urlList = urls.iterator(); urlList.hasNext();) {
                URL url = (URL)urlList.next();
                Image image = Toolkit.getDefaultToolkit().getImage(url);
                if(image != null) {
                    imageURL = url;
                    texture = new TextureLoader(image, "RGB", observer);
                    break;
                }
            }
            if(texture != null && texture.getTexture() != null) {
                appearance.setTexture(texture.getTexture());
                System.out.println("Texture loaded from \"" + imageURL + "\"");
            } else {
                //for(URL url : urls) {
                for(Iterator urlList = urls.iterator(); urlList.hasNext();) {
                    URL url = (URL)urlList.next();
                    System.out.println("No texture loaded from \"" + url + "\"");
                }
            }
	    
	    head.addChild(new Sphere(earthRadius,
				     Sphere.GENERATE_NORMALS | Sphere.GENERATE_TEXTURE_COORDS,
				     180, appearance));
	} else {
	    earthRadius = 0;
	}

	Transform3D transform = new Transform3D();
	platformRotation = new TransformGroup(transform);
	platformRotation.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	platformRotation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	head.addChild(platformRotation);

	transform = new Transform3D();
	transform.setTranslation(new Vector3f(0f, 0f, earthRadius));
	sceneRoot = new TransformGroup(transform);
	sceneRoot.setCapability(Group.ALLOW_CHILDREN_READ);
	sceneRoot.setCapability(Group.ALLOW_CHILDREN_WRITE);	
	sceneRoot.setCapability(Group.ALLOW_CHILDREN_EXTEND);
	platformRotation.addChild(sceneRoot);

	this.earthRadius = earthRadius;
    }

    public void makeLive() {
	locale.addBranchGraph(head);
    }

    public TransformGroup getRoot() {
	return sceneRoot;
    }

    public TransformGroup getPlatformRotation() {
	return platformRotation;
    }

    public BranchGroup getHead() {
	return head;
    }

    public float getEarthRadius() {
	return earthRadius;
    }
}

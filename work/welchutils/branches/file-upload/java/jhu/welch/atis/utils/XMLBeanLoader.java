package jhu.welch.atis.utils;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Unmarshaller;

public class XMLBeanLoader {
    private static XMLBeanLoader instance = null; 
    private Object locker = new Object();
	
    /**
     * Private constructor
     */
    private XMLBeanLoader() {
        // do nothing
    }
	
    /**
     * 
     * Get an instance of XMLBeanLoader
     * 
     * @return XMLBeanLoader Return an instance of XMLBeanLoader
     */
    public static XMLBeanLoader getInstance(){
        if( instance == null ) { 
            instance = new XMLBeanLoader();
        }
	
        return instance; 
    }
	
    /**
     * Load a Java Bean object from a matching XML file 
     * 
     * @param aClass A Java Bean class
     * 
     * @return Object A matching Java Bean Object
     */
    public Object loadBean(Class<?> aClass, String beanXMLFile){
        Object obj;
	
        if( beanXMLFile == null ) {
            throw new RuntimeException( "Missing xml file" );
        }
		
        synchronized( locker ) {
            obj = null;

            try {
                JAXBContext context = JAXBContext.newInstance( aClass );
                Unmarshaller um = context.createUnmarshaller();

                obj = um.unmarshal( ClassLoader.getSystemResourceAsStream( beanXMLFile ) );
            } catch (JAXBException jaxbE) {
                jaxbE.printStackTrace();
                throw new RuntimeException( "JAXB error: failed to load "
                                            + beanXMLFile
                                            + " with the message: \n"
                                            + jaxbE.getMessage(), jaxbE );
            }
        }
		
        return obj;
    }
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package client;

import java.rmi.RemoteException;
import javax.xml.rpc.ServiceException;

/**
 *
 * @author Kaspars
 */
public class Client {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) throws RemoteException, ServiceException {
        
        String token = "4gTAdSxPUxEaII8";
        
        example4(token);
    }
    
    public static void example1(String token) throws RemoteException, ServiceException
    {
        AppControllersSoapControllerService service = new AppControllersSoapControllerService_Impl ();
        AppControllersSoapControllerPort port = service.getAppControllersSoapControllerPort();
        
        Integer newModel = port.actionCreateModel(token);
        
        System.out.println("Created new data model with id: " + Integer.toString(newModel));
    }
    
    public static void example2(String token) throws RemoteException, ServiceException
    {
        AppControllersSoapControllerService service = new AppControllersSoapControllerService_Impl ();
        AppControllersSoapControllerPort port = service.getAppControllersSoapControllerPort();
        
        Integer newModel = port.actionCreateModel(token);
        
        String[] trainingSet = new String[5];
        trainingSet[0] = "https://static.pexels.com/photos/36753/flower-purple-lical-blosso.jpg";
        trainingSet[1] = "https://static.pexels.com/photos/60597/dahlia-red-blossom-bloom-60597.jpeg";
        trainingSet[2] = "https://static.pexels.com/photos/39517/rose-flower-blossom-bloom-39517.jpeg";
        trainingSet[3] = "https://upload.wikimedia.org/wikipedia/commons/thumb/5/52/Liliumbulbiferumflowertop.jpg/220px-Liliumbulbiferumflowertop.jpg";
        trainingSet[4] = "https://cdn.theatlantic.com/assets/media/img/mt/2017/10/Pict1_Ursinia_calendulifolia/lead_960.jpg?1508330040";
        
        String reponse = port.actionTrainArray(token, newModel, trainingSet);
        
        System.out.println(reponse);
    }
    
    public static void example3(String token) throws RemoteException, ServiceException
    {
        AppControllersSoapControllerService service = new AppControllersSoapControllerService_Impl ();
        AppControllersSoapControllerPort port = service.getAppControllersSoapControllerPort();
        
        String reponse = port.actionTest(token, 1, "https://cdn.images.dailystar.co.uk/dynamic/28/photos/946000/620x/Plane-painted-white-548527.jpg");
        
        System.out.println(reponse);
    }
    
    public static void example4(String token) throws RemoteException, ServiceException
    {
        AppControllersSoapControllerService service = new AppControllersSoapControllerService_Impl ();
        AppControllersSoapControllerPort port = service.getAppControllersSoapControllerPort();
        
        String[] testSet = new String[2];
        testSet[0] = "http://i2.cdn.turner.com/cnn/2016/images/02/02/a330-cover.jpg";
        testSet[1] = "https://i.ytimg.com/vi/YB7kkcLbndw/hqdefault.jpg";
        
        String reponse = port.actionTestArray(token, 1, testSet);
        
        System.out.println(reponse);
    }
}

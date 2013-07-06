
import org.jivesoftware.smack.ConnectionConfiguration;
import org.jivesoftware.smack.Roster;
import org.jivesoftware.smack.RosterEntry;
import org.jivesoftware.smack.RosterGroup;
import org.jivesoftware.smack.PacketListener;
import org.jivesoftware.smack.RosterListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.SASLAuthentication;
import org.jivesoftware.smack.filter.MessageTypeFilter;
import org.jivesoftware.smack.filter.PacketFilter;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.Packet;
import org.jivesoftware.smack.packet.Presence;

    public class Greeter implements RosterListener {
        private XMPPConnection connection;

        public Greeter( XMPPConnection connection ) {
            this.connection = connection;
        }
        
        public void entriesAdded( Collection<String> addresses ) {}
        public void entriesDeleted( Collection<String> addresses ) {}
        public void entriesUpdated( Collection<String> addresses ) {}

        public void presenceChanged( Presence presence ) {
            log.info("Got presence: " + presence.getType() );
            
            if( presence.getType() == Presence.Type.subscribe ) {
                Presence reply = new Presence( Presence.Type.subscribed );
                reply.setTo( presence.getFrom() );
                connection.sendPacket( reply );
            }
        }
    };

    public Object chatList( final String path, final Callback callback ) {
        return get( "connection",
                    new Callback() {
                        public Object call( Object data ) {
                            final XMPPConnection connection =
                                (XMPPConnection)data;
                            Stack<Map<String,Object>> paths = null;
                            try {
                                paths =
                                    (Stack<Map<String,Object>>)AccessController.doPrivileged
                                    ( new PrivilegedAction() {
                                            public Object run() {
                                                //PacketFilter filter = new MessageTypeFilter( Message.Type.chat );
                                                //connection.addPacketListener( new MessageParrot( connection ), filter );
                                                Stack<Map<String,Object>> paths =
                                                    new Stack<Map<String,Object>>();
                                                
                                                Roster roster = connection.getRoster();
                                                // roster.setSubscriptionMode( Roster.SubscriptionMode.manual );
                                                // roster.addRosterListener( new Greeter( connection ) );
                                                
                                                if( path.length() == 0 ) {
                                                    for( RosterEntry entry : roster.getEntries() ) {
                                                        Map<String,Object> obj =
                                                            new HashMap<String,Object>();
                                
                                                        String name = entry.getName();
                                                        String username = entry.getUser();
                                                        if( name == null ) {
                                                            name = username;
                                                        }
                                                        obj.put( "name", name );
                                                        obj.put( "username", username );
                                                 
                                                        Pattern botName = Pattern.compile( "^" + username
                                                                                           + "/mimis/bot"
                                                                                           + "/(.*)/([^/]+)/?" );
                                                        log.info( "name:" + username );
                                                        for( Iterator<Presence> presences =
                                                                 roster.getPresences( username );
                                                             presences.hasNext(); ) {
                                                            Presence presence = (Presence)presences.next();
                                                            Matcher match = botName.matcher( presence.getFrom() );
                                                            log.info( "presence:" + presence.getFrom() + ":" + presence.toString() );
                                                            if( match.matches() ) {
                                                                String location = match.group( 1 );
                                                                String id = match.group( 2 );
                                                                log.info( "presence:" + presence.toString() );
                                                                obj.put( "location", location );
                                                            }
                                                        }
                                                        paths.push( obj );
                                                    }
                                                } else {
                                                    // google bounces back the default message types, you must use chat
                                                    Message msg = new Message( "wholcomb@gmail.com",
                                                                               Message.Type.chat );
                                                    msg.setBody( "Test" );
                                                    connection.sendPacket( msg );
                                                }
                                                return paths;
                                            }
                                        } );
                                if( callback != null ) {
                                    callback.call( paths );
                                }
                            } catch( Exception e ) {
                                e.printStackTrace();
                                log.warning( e.getClass().getName() );
                            }
                            return paths;
                        }
                    } );
    }


                localSet( "connection",
                          new DynamicValue() {
                              XMPPConnection connection = null;
                              String username;
                              String password;
                              String server;
                              
                              public Object value( final Callback callback ) {
                                  log.info( "get:connection" );
                                  // Called when the username and password callbacks return
                                  final Callback connector = new Callback() {
                                          public Object call( Object data ) {
                                              return AccessController.doPrivileged
                                                  ( new PrivilegedAction() {
                                                          public Object run() {
                                                              if( username != null && password != null && connection == null ) {
                                                                  String domain = username.substring( username.lastIndexOf( "@" ) + 1 );
                                                                  if( server == null || server.length() == 0 ) {
                                                                      server = domain;
                                                                  }

                                                                  int port = 5222;
                                                                  log.info( "connector:" + username + ":" + server );
                                                                  
                                                                  ConnectionConfiguration connConfig =
                                                                      new ConnectionConfiguration( server, port, domain );
                                                                  connection = new XMPPConnection( connConfig );
                                                                  
                                                                  try {
                                                                      connection.connect();
                                                                      log.info( "Connected to " + connection.getHost() );
                                                                      
                                                                      SASLAuthentication.supportSASLMechanism( "PLAIN", 0 );
                                                                      
                                                                      connection.login( username,
                                                                                        password,
                                                                                        "mimis/bot/" + localGet( "local.hostname" ) + "/" );
                                                                      
                                                                      log.info( "Logged in as " + connection.getUser() );
                                                                      
                                                                      Presence presence = new Presence( Presence.Type.available );
                                                                      connection.sendPacket( presence );
                                                                  } catch( XMPPException ex ) {
                                                                      ex.printStackTrace();
                                                                      // XMPPConnection only remember the username if login is succesful
                                                                      // so we can't use connection.getUser() unless we log in correctly
                                                                      connection = null;
                                                                      log.warning( "Failed to log in as " + username );
                                                                  }
                                                              }
                                                              if( callback != null && connection != null ) {
                                                                  callback.call( connection );
                                                              }
                                                              return connection;
                                                          }
                                                      } );
                                          }
                                      };
                                  browserGet( "mimis.xmpp.credentials",
                                              new Callback() {
                                                  public Object call( Object data ) {
                                                      JSObject credentials = (JSObject)data;
                                                      username = (String)credentials.getMember( "username" );
                                                      password = (String)credentials.getMember( "password" );
                                                      server = (String)credentials.getMember( "server" );
                                                      log.info( "Cred:" + username );
                                                      connector.call( null );
                                                      return data;
                                                  }
                                              } );

                                  return connection;
                              }
                          } );

                ((JSObject)browserGet( "mimis.applet" ))
                    .call( "load",
                           new Object[] { makeJSObject( store ) } );
            } catch( JSException e ) {
                log.warning( "Callback Failed: " + e.getMessage() );
            }
        } catch( Exception e ) {
            e.printStackTrace();
            log.warning( "Failed to Load: " + e.getMessage()
                         + " (" + e.getClass().getName() + ")" );
        }
    }


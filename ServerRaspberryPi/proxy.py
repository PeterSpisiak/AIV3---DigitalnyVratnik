# Python 3 server example
from http.server import BaseHTTPRequestHandler, HTTPServer
import time
from datetime import datetime
import mysql.connector

hostName = "0.0.0.0"
serverPort = 8000
sql = "INSERT INTO `prichody` (`id`, `zamestnanec_id`, `terminal_id`, `cas`) VALUES (NULL, %, %, NOW);"
val = ("John", "Highway 21")
class MyServer(BaseHTTPRequestHandler):
    def do_GET(self):
        self.send_response(200)
        self.send_header("Content-type", "text/html")
        self.end_headers()
        mydb = mysql.connector.connect(
            host="localhost",
            user="Dochadzka",
            password="klopklop296",
            database="dochadzka"
        )
        mycursor = mydb.cursor()
        terminalId = 1
        mycursor.execute("SELECT id FROM `zamestnanci` WHERE `karta_id` LIKE '"+self.path[1:]+"' ")
        result = mycursor.fetchone()
        now = datetime.now()
        current_time = now.strftime("%H:%M:%S")
        if(result != None):
            userId = result[0]
            mycursor.execute("SELECT * FROM `prichody` WHERE `zamestnanec_id` = " + str(userId) + " AND DATE(cas) = CURDATE()")
            result = mycursor.fetchone()
            if(result == None):
                print(userId)
                print(terminalId)

                result = mycursor.execute("INSERT INTO `prichody` (`id`, `zamestnanec_id`, `terminal_id`, `cas`) VALUES (NULL, %s, %s, NOW());", (userId, terminalId))
                print("Prichod zamestnanca s ID " + str(userId) + " cez terminal s id " + str(terminalId) + " o case " + current_time)
                mydb.commit()
            else:
                mycursor.execute(
                    "SELECT * FROM `odchody` WHERE `zamestnanec_id` = " + str(userId) + " AND DATE(cas) = CURDATE()")
                result = mycursor.fetchone()
                if(result == None):
                    result = mycursor.execute("INSERT INTO `odchody` (`id`, `zamestnanec_id`, `terminal_id`, `cas`) VALUES (NULL, %s, %s, NOW());", (userId, terminalId))
                    print(
                        "Odchod zamestnanca s ID " + str(userId) + " cez terminal s id " + str(terminalId) + " o case " + current_time)
                    mydb.commit()
        mydb.disconnect()

if __name__ == "__main__":        
    webServer = HTTPServer((hostName, serverPort), MyServer)
    print("Server started http://%s:%s" % (hostName, serverPort))


    try:
        webServer.serve_forever()
    except KeyboardInterrupt:
        pass

    webServer.server_close()
    print("Server stopped.")
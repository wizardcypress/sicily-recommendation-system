
import MySQLdb;
from random import randrange;
def __main__():

    conn = MySQLdb.connect(host='localhost',user='root',passwd='5216778',charset='UTF8', db='sicily');
    cursor = conn.cursor();

    cursor.execute("SELECT pid FROM problems");
    probs = [it[0] for it in cursor.fetchall()];

    for i in range(0,18):
        print probs[randrange(0,probs.__len__())];

__main__();




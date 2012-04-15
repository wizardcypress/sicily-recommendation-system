import urllib2
import BeautifulSoup
from BeautifulSoup import BeautifulSoup
import re
import MySQLdb
import string

_url = 'http://soj.me/status.php'

def __main__():

    conn = MySQLdb.connect(host='localhost',user='root',passwd='5216778',charset='UTF8', db='sicily');
    cursor = conn.cursor();

    for i in range(70500, 75500):
        print i;
        url = _url + '?p=' + str(i)
        problem = urllib2.urlopen(url)
        soup = BeautifulSoup(problem.read())

        tr = soup.findAll('tr');

        for j in range(1, tr.__len__()):
            try:
                td = tr[j].findAll('td');
                sid = string.atoi(td[0].next);
                uid = td[2].next.next['href'][12:]
                username = td[2].next.next.next;
                pid = td[3].next.next;
                language = td[4].next;
                status = td[5].next.next.next;
                if type(status) == type(tr[0]):
                    status = status.next;


                run_time = string.replace(td[6].next, 'sec','')[1:];
                run_time = string.replace(run_time,'&gt;','');
                run_memory = string.replace(td[7].next, 'KB','');
                codelength = string.replace(td[8].next, 'Bytes','');
                submit_time = td[9].next;
                #print '|'.join([str(sid),username,uid, pid,language,status,run_time,run_memory, codelength, submit_time]);

                sql = """insert into status(sid,uid,pid,language,status,run_time,run_memory,time,codelength) values(%s,%s,%s,%s,%s,%s,%s,%s,%s)""";

                cursor.execute(sql, (sid,uid,pid,language,status,run_time,run_memory,submit_time,codelength));
            except:
                print 'duplicate';
                continue;

        conn.commit();

__main__()


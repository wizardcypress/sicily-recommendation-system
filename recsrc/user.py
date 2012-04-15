import urllib2
import BeautifulSoup
from BeautifulSoup import BeautifulSoup
import re
import MySQLdb;

_url = 'http://soj.me/user.php'


def __main__():

    conn = MySQLdb.connect(host='localhost',user='root',passwd='5216778',charset='UTF8', db='sicily');
    cursor = conn.cursor();

    for i in range(4, 17000):
        url = _url + '?id=' + str(i)
        problem = urllib2.urlopen(url)
        soup = BeautifulSoup(problem.read())
        head =soup.findAll('tr')

        if head.__len__() < 8:
            print 'no such user';
            continue;
        username = head[1].find('th').next[10:];
        uid = i;
        nickname = head[2].findAll('td')[1].next;
        signature = head[3].findAll('td')[1].next;
        solved = head[5].findAll('td')[1].next;
        submit = head[6].findAll('td')[1].next;
        email = head[7].findAll('td')[1].next.next.next;
        address = head[8].findAll('td')[1].next;

        print 'uid:',uid;
        print 'username', username;
        print 'nickname:',nickname;
        print 'signature:',signature;
        print 'solved:',solved;
        print 'submit:',submit;
        print 'email:',email;
        print 'address:',address;

        sql = """insert into user(uid,username,nickname,signature,solved,submissions,email,address) values(%s,%s,%s,%s,%s,%s,%s,%s)""";

        cursor.execute(sql, (uid, username, nickname, signature, solved, submit, email, address));
        conn.commit();

__main__()



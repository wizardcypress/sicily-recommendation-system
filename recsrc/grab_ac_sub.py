#!/usr/bin/python

import urllib2
import BeautifulSoup
from BeautifulSoup import BeautifulSoup
import re
import MySQLdb;

_url = 'http://soj.me/'


def convert_name(key):
    key = key.lower();
    if key == 'sample input': return 'sample_input';
    if key == 'sample output': return 'sample_output';
    return key;

def __main__():

    conn = MySQLdb.connect(host='localhost',user='root',passwd='5216778',charset='UTF8', db='sicily');
    cursor = conn.cursor();

    cursor.execute("SELECT pid FROM problems");
    probs = [it[0] for it in cursor.fetchall()];
    for i in probs:
        if i:
            print i;
            dict = {}
            url = _url + str(i);
            req = urllib2.Request(url);
            problem = urllib2.urlopen(url);
            soup = BeautifulSoup(problem.read());
            hord_list = soup.findAll('dd',{'class':'hord_dd'});

            accepted=hord_list[0].next;
            submissions=hord_list[1].next;
            sql = "UPDATE problems set accepted=%s,submissions=%s WHERE pid=%s";

            cursor.execute(sql, (accepted, submissions,i));
            #content = post_body.contents;
            #text = '';
            #head = '';
            #for p in content:
            #    try:
            #        if hasattr(p,'name'):
            #            if p.name == 'center':
            #                dict['title'] = p.next.next;
            #            elif p.name == 'h1':
            #                if head != '':
            #                    dict[head.next] = text;
            #                head = p;
            #                text = '';
            #            else:
            #                text = text + str(p);
            #    except UnicodeDecodeError:
            #        print 'UnicodeError';
            #        break;
            #if head != '':
            #    dict[head.next] = text

            ##convert to problems

            #prob = {};
            #prob['pid'] = i;
            #for item in dict.items():
            #    key = item[0];
            #    val = item[1];
            #    key = convert_name(key);
            #    prob[key] = val;

            #sql = """insert into problems(pid,title,description,input,output,sample_input,sample_output) values(%s,%s,%s,%s,%s,%s,%s)"""

            #cursor.execute(sql,(i,prob['title'],prob['description'],prob['input'],prob['output'],prob['sample_input'],prob['sample_output']));
            ##sql = sql % (i,prob['title'],prob['description'],prob['input'],prob['output'],prob['sample_input'],prob['sample_output']);
            ##print sql;

        #except :
        #    print 'errro';
        #    continue;

__main__();



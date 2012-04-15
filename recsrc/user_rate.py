import urllib2
import re
import MySQLdb
import math

def evalue(right, wrong, rightNum, submitNum):

    if submitNum == 0: return 0;
    weight = 1.0*rightNum / submitNum;
    return math.log(right * (1-weight) + wrong * weight + 1);

    # wrong = math.log(wrong+2,2);
    # if right > 5: right = 5;
    # return right + wrong;

def __main__():

    conn = MySQLdb.connect(host='localhost',user='root',passwd='5216778',charset='UTF8', db='sicily');
    cursor = conn.cursor();

    cursor.execute("DELETE FROM user_rate;");

    cursor.execute("SELECT uid FROM user");
    users = [it[0] for it in cursor.fetchall()];

    for uid in users:
        print uid;
        sql = "SELECT uid,pid,status from status where uid = %d ;" % uid;
        cursor.execute(sql);
        res = cursor.fetchall();
        wrong = 0;
        right = 0;
        n = res.__len__();

        cnt = {};
        for i in range(0,n):
            cnt.setdefault(res[i][1], [0,0,0,0]);
            if res[i][2] == "Accepted" : cnt[res[i][1]][0] += 1;
            else: cnt[res[i][1]][1] += 1;

        sql = "SELECT accepted,submissions from problems WHERE pid = %s ; ";
        for prob in cnt:
            cursor.execute(sql, (prob));
            tmp = cursor.fetchall();
            if len(tmp) == 1:
                cnt[prob][2] = tmp[0][0];
                cnt[prob][3] = tmp[0][1];

        if(n):
            sql = "INSERT INTO user_rate values(" + str(uid) + ", %s, %s, %s, %s)" ;
            for item in cnt.items():
                val = evalue(item[1][0], item[1][1], item[1][2], item[1][3]);
                cursor.execute(sql, (item[0], item[1][1], item[1][0], val));

    conn.commit();

__main__()



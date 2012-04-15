import MySQLdb;
from math import sqrt;
from math import log;

def getProblemRate(array):
    dir = {};
    for item in array:
        dir[item[0]] = item[1]; #rate
    return dir;

def getProblemInfo(array):
    dir = [0,0];
    for item in array:
        dir[0] = item[0]; #accepted
        dir[1] = item[1]; #submissions
    return dir;

def sim_pearson(v1, v2):
    si = {};
    for item in v1:
        if item in v2: si[item] = 1;

    n = len(si);
    if n == 0: return 1;

    sum1 = sum([v1[it] for it in si]);
    sum2 = sum([v2[it] for it in si]);

    sum1sq = sum([pow(v1[it],2) for it in si]);
    sum2sq = sum([pow(v2[it],2) for it in si]);

    psum = sum([v1[it]*v2[it] for it in si]);

    num = psum - sum1*sum2/n;
    den = sqrt(abs((sum1sq-pow(sum1,2)/n)*(sum2sq-pow(sum2,2)/n)))
    if den == 0: return 0;

    return num/den;

def dot_cross(v1, v2, dist1, dist2):

    if dist1 == 0 or dist2 == 0: return 0;

    n = len(v2);
    dot_sum = sum([v1[i] * v2[i] for i in range(0,n)])

    return abs(dot_sum/(dist1*dist2));


def sim_combine(rate1, rate2, info1, info2):

    dist1 = sqrt(sum([ pow(it, 2) for it in rate1.itervalues()]));
    dist2 = sqrt(sum([ pow(it, 2) for it in rate2.itervalues()]));

    v1 = [];
    v2 = [];
    for item in rate1:
        if item in rate2:
            v1.append(rate1[item]);
            v2.append(rate2[item]);

    ACmin = min(info1[0], info2[0])
    ACmax = max(info1[0], info2[0])
    SUBmin = min(info1[1], info2[1])
    SUBmax = max(info1[1], info2[1])

    if SUBmin == 0: return 0;
    if ACmin == 0: simFactor = 1.0 * log(SUBmin+1) / log(SUBmax+2);
    else: simFactor = (1.0 * log(SUBmin+1) / log(SUBmax+2)) * (1.0 * log(ACmin+1) / log(ACmax+2));

    return 0.8 * dot_cross(v1, v2, dist1, dist2) + 0.2 * simFactor;


def __main__(distance):

    conn = MySQLdb.connect(host='localhost',user='root',passwd='5216778',charset='UTF8', db='sicily');
    cursor = conn.cursor();

    cursor.execute("DELETE from prob_sim;");

    cursor.execute("SELECT pid from problems;");
    probs = [item[0] for item in cursor.fetchall()];

    problemRateVector = {};
    problemOverallInfo = {};
    for i in probs:
        print "selecting %d" % i;
        sql = "SELECT uid,rate FROM user_rate WHERE pid = %s; ";
        cursor.execute(sql, (i));
        problemRateVector[i] = getProblemRate(cursor.fetchall());

        sql = "SELECT accepted,submissions FROM problems WHERE pid = %s";
        cursor.execute(sql, (i));
        problemOverallInfo[i] = getProblemInfo(cursor.fetchall());


    for idx1 in range(0,probs.__len__()):
        for idx2 in range(idx1+1, probs.__len__()):
            p1 = probs[idx1];
            p2 = probs[idx2];
            sim = distance(problemRateVector[p1], problemRateVector[p2], problemOverallInfo[p1], problemOverallInfo[p2]);

#            if sim <= 1:
            print p1,p2,sim
            cursor.execute("INSERT INTO prob_sim values(%s,%s,%s);", (p1,p2,sim));

    conn.commit();

__main__(sim_combine)



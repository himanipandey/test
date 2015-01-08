import logging
import os
import requests
import subprocess
import MySQLdb

# create logger with '__name__'
logger = logging.getLogger(__name__)
logger.setLevel(logging.DEBUG)
# create file handler which logs even debug messages
fh = logging.FileHandler('poster.log')
fh.setLevel(logging.DEBUG)
# create console handler with a higher log level
ch = logging.StreamHandler()
ch.setLevel(logging.ERROR)
# create formatter and add it to the handlers
formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
fh.setFormatter(formatter)
ch.setFormatter(formatter)
# add the handlers to the logger
logger.addHandler(fh)
logger.addHandler(ch)

db = MySQLdb.connect('10.0.38.170', 'cms', 'CMS@123', 'cms') 
cursor = db.cursor()

#target_url = 'https://proptiger.com/data/v1/entity/audio'

target_url = 'https://beta-new.proptiger-ws.com/data/v1/entity/audio'


def download_audio(url, CallId):
    filename = url.split('/')[-1]
    FNULL = open(os.devnull, 'w')
    subprocess.call(['wget', url], stdout=FNULL, stderr=FNULL)
    if os.path.isfile(filename):
        logger.debug('%s has downloaded from %s for Call Id-%s'%(filename, url, CallId))
        return filename
    else:
        logger.warning('%s could not download from %s for Call Id-%s'%(filename, url, CallId))
        return None


def audio_poster(objectType, objectId, documentType, priority, filename):
    payload = {"objectType":objectType, "objectId":objectId, "documentType":documentType, "priority":priority}
    files = {'file': open(filename, 'rb')}
    #print 'alok:',(target_url, payload, files)
    r = requests.post(target_url, data=payload, files=files)
    #print 'alok2',r.text
    if r.status_code==200:
        logger.debug('%s has posted for Call Id-%s'%(filename, objectId))
        os.remove(filename)
        return r.json()['absoluteUrl']
    else:
        logger.warning('%s could not posted for Call Id-%s'%(filename, objectId))
        return r.json()['error']['msg']

def update_status(callID):
    query = 'update CallDetails set media_service_status="Uploaded" where callID="%s"'%(callID)
    try:
        cursor.execute(query)
        db.commit()
    except:
        db.rollback()
        

if __name__=='__main__':
    query = 'select CallId, AudioLink from CallDetails where AudioLink regexp "mp3" and media_service_status="NotUploaded" and CreationTime> DATE_SUB(NOW(), INTERVAL 20 DAY) order by CallId desc '
    cursor.execute(query)
    rows = cursor.fetchall()

    for row in rows:
        AudioLink = row[1]
        CallId = str(int(row[0]))
        #logger.debug('processing %s'%(AudioLink))
        filename = download_audio(AudioLink, CallId)
        if filename:
            status = audio_poster('call', CallId, 'recording', '1', filename)
            if 'mp3' in status or 'Media Already Exists' in status:
                update_status(CallId)





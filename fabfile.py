from fabric.api import *
#fab -Hsysadmin@180.179.212.8 prod:tagname  this is the command for the code to be deployed on noida-2 server
def prod(tag):
  local("git archive --format tar.gz --output /tmp/cms.tar.gz refs/tags/%s^{}" %(tag))
  put('/tmp/cms.tar.gz', '/tmp')
  run("cd /home/sysadmin && mkdir cms_new && cd cms_new && tar -xf /tmp/cms.tar.gz && ln -s /home/sysadmin/public_html/images_new images_new && cp -r /home/sysadmin/cms_config_prod/* . && cd /home/sysadmin && mv production_cms cms_old && mv cms_new production_cms && echo %s > production_cms/version && sudo rm -rf /tmp/*.tpl.php && rm -r cms_old" %(tag))

#fab -Hsysadmin@cms.proptiger-ws.com staging:branchname  this is the command for the code to be deployed on staging server
def staging(tag):
  local("git archive --format tar.gz --output /tmp/cms.tar.gz remotes/origin/%s" %(tag))
  put('/tmp/cms.tar.gz', '/tmp')
  run("cd /home/sysadmin && mkdir cms_new && cd cms_new && tar -xf /tmp/cms.tar.gz && ln -s /home/sysadmin/public_html/images_new images_new && cp -r /home/sysadmin/cms_config_staging/* . && cd /home/sysadmin && mv cms cms_old && mv cms_new cms && echo %s > cms/version && sudo rm -rf /tmp/*.tpl.php && rm -r cms_old" %(tag))

#!/bin/bash

# /etc/crontab
# ## Run hosts.allow allow-list evaluator once a minute
# 1 * * * * root /path/to/alohomora/cron.sh
# ## Restore the original/static hosts.allow file once a day
# * 23  * * * root /bin/cp /etc/hosts.allow.orig /etc/hosts.allow
#  ## Restore iptables to the default rules once a day ( System V )
# * 23  * * * root /etc/init.d/iptables restart
# ## Restore iptables to the default rules once a day ( systemd )
# * 23  * * * root systemctl iptables restart

FILE="$(dirname "$0")/people.txt"

# actions needs to be modded from actions.sample.sh
#source actions.sh
# below is POSIX compliant way to source.
. actions.sh

function valid_ip() {
   # Test an IP address for validity:
   # code from: http://www.linuxjournal.com/content/validating-ip-address-bash-script

   local  ip=$1
   local  stat=1

   if [[ $ip =~ ^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$ ]]; then
       OIFS=$IFS; IFS='.'; ip=($ip); IFS=$OIFS;
       [[ ${ip[0]} -le 255 && ${ip[1]} -le 255 && ${ip[2]} -le 255 && ${ip[3]} -le 255 ]]
       stat=$?
   fi
   return $stat
}

if [ -f $FILE ]; then

  while IFS=' ' read -r USER IP; do
    if valid_ip $IP; then
      user_actions $USER $IP
    fi

  done < <(sed -e 's/^\([A-Z]\{3\}\)\ \([0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\)$/\1 \2/' $FILE)

  echo "" > $FILE

fi

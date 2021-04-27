#!/bin/bash

# Username pattern is a 3 char uppercase alpha string.
# The same username needs to be in the $pws array in config.php

function user_actions() {
  local USER=$1
  local IP=$2

  case $USER in
    CPC)
      /bin/echo "## $USER remote admin" >> /etc/hosts.allow
      /bin/echo "## $(/bin/date)" >> /etc/hosts.allow
      /bin/echo "sshd: $IP : severity local0.alert" >> /etc/hosts.allow
      /bin/echo "#" >> /etc/hosts.allow
      /bin/echo "" >> /etc/hosts.allow
      /sbin/iptables -I INPUT -p tcp -s $IP --dport=43322 -j ACCEPT
      /sbin/iptables -I INPUT -p tcp -s $IP --dport=22334 -j ACCEPT
      /sbin/iptables -I INPUT -p tcp -s $IP --dport=44444 -j ACCEPT
      ;;

    VAL)
      # do things just for this user
      /bin/echo "## $USER Valheim Access" >> /etc/hosts.allow
      /bin/echo "## $(/bin/date)" >> /etc/hosts.allow
      /bin/echo "valheim: $IP : severity local0.alert" >> /etc/hosts.allow
      /bin/echo "#" >> /etc/hosts.allow
      /bin/echo "" >> /etc/hosts.allow
      /sbin/iptables -I INPUT -p tcp -s $IP --dport=2456 -j ACCEPT
      ;;

    esac
  }

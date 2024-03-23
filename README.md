# Durin's Door

**_Speak, friend, and enter._**

Allow temporary remote access to internal services to approved IPs by a simple form.  Click the lock, enter your user/service password, and open the port(s).

Drop into your desired https endpoint, save your changes of config.sample.php to config.php, and go!
  
Centered around [MikroTik](https://mikrotik.com/) routers and their [API](https://help.mikrotik.com/docs/display/ROS/REST+API), this allows you to add IPs to address-lists, granting access for a set time interval.

Have a Minecraft server at home that you don't want exposed to anyone but your friends?

1. Create the following MikroTik firewall rule _(you may need to specify dst-address=107.132.227.23)_:
   `action=netmap chain=dstnat comment=PiCraft dst-port=25565 protocol=tcp src-address-list=allowMINE to-addresses=10.0.0.212 to-ports=25565`
2. Add the following to your **config.php** $pws array:
   `'Cr4fty PW!' => array('user' => 'MINE', 'expire' => '8 hours'),`

As long as the user is appended to the list name ( user: **MINE**, list: allow**MINE** ), you should be good to go.  You can use this with address-lists based on a person, or a service - it all depends on how you set up your address-lists and firewall rules.

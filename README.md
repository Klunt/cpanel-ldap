Cpanel LDAP
=========

A simplified cpanel to manage LDAP server. It's based on Phamm schema and adds a VPN Organization Unit in which users can be added in order to authenticate themselves against LDAP.



## Requirements

* LDAP Server
* Phamm schema
* OpenVPN server installed and running whit authentication against ldap
* PHP
* Apache
* libnss-ldapd libpam-ldapd

## System settings

* Create a group named sftpusers
* Vhosts created by the script will be located in /etc/apache/ldap-enabled/ so these steps are required:  
 -  Create folder /etc/apache/ldap-enabled/
 -  In /etc/apache/apache2.conf add  

```
IncludeOptional ldap-enabled/*.conf

```
* Configure nslcd.conf according to your system. An example:

```
#search scope.
scope sub 
#Change it
base passwd ou=People,dc=example,dc=tld
base group ou=Group,dc=example,dc=tld

# The DN to bind with for normal lookups. Change it with your data
 binddn cn=phamm,o=hosting,dc=example,dc=tld
 bindpw rhx 
    
pagesize 1000
        
## In this example, wu don't want root to be listed in ldap
filter passwd (&(objectClass=person)(loginShell=/bin/bash)(!(uid=root)))
filter shadow (&(objectClass=person)(loginShell=/bin/bash)(!(uid=root)))

```

* If you have custom olcAcces rules maybe you need to add the following  at the beginning of your config file (something like olcDatabase\=\{1\}mdb.ldif:

```
olcAccess: {0}to attrs=userPassword,shadowLastChange by self write by anonymous auth by * none
olcAccess: {1}to dn.base="" by * read
olcAccess: {2}to * by * read
```

* Make sure you hace ldap enabled in nsswitch and pam
  Files should be something like this:

- /etc/nsswitch.conf

```
passwd:         compat ldap
group:          compat
shadow:         compat ldap
#gshadow:        files ldap

hosts:          files dns 
networks:       files

protocols:      db files
services:       db files
ethers:         db files
rpc:            db files

netgroup:       nis

```
- in /etc/pam.d/common-session

```
session [success=ok default=ignore] pam_ldap.so minimum_uid=1000
```

- In /etc/pam.d/common-session

```
session [success=ok default=ignore] pam_ldap.so minimum_uid=1000
# To automatically create home on first user login
session required   pam_mkhomedir.so skel=/etc/skel/ umask=0077
```
- In /etc/pam.d/common-auth

```
    auth    [success=1 default=ignore]      pam_ldap.so minimum_uid=1000 use_first_pass
```

- In /etc/pam.d/common-password

```
password        [success=1 default=ignore]      pam_ldap.so minimum_uid=1000 try_first_pass

```
- In /etc/pam.d/common-account

```
account [success=ok new_authtok_reqd=done ignore=ignore user_unknown=ignore authinfo_unavail=ignore default=bad]            pam_ldap.so minimum_uid=1000

```

* /etc/ssh/sshd_cnfig Confoguration to jail users in their home 

```
AcceptEnv LANG LC_*
ChallengeResponseAuthentication no
PrintMotd no
#Subsystem sftp /usr/lib/openssh/sftp-server - Lo cambiamos por la siguiente línea
Subsystem sftp internal-sftp
UsePAM yes
X11Forwarding yes
# Añadimos la condición para los usuarios que pertenecen al grupo sftpusers
Match Group sftpusers
# Force the connection to use SFTP and chroot to the required directory.
ForceCommand internal-sftp
ChrootDirectory /home/sftpusers
# Disable tunneling, authentication agent, TCP and X11 forwarding.
PermitTunnel no
AllowAgentForwarding no
AllowTcpForwarding no
X11Forwarding no

```
* Add UidNext Objectclass to ldap. This is used to simulate autoincrement
 
```
#uidNext.schema

objectclass ( 1.3.6.1.4.1.19173.2.2.2.8
NAME 'uidNext'
DESC 'Field to get netx uidnNumber from'
SUP top STRUCTURAL
MAY ( cn $ uidNumber )
)
```
uidNext.ldif to import, generated by schema

```
# AUTO-GENERATED FILE - DO NOT EDIT!! Use ldapmodify.
# CRC32 2343cd4a
dn: cn=uidnext,cn=schema,cn=config
objectClass: olcSchemaConfig
cn: uidnext
olcObjectClasses: {0}( 1.3.6.1.4.1.19173.2.2.2.8 NAME 'uidNext' DESC 'Field 
 to get netx uidnNumber from' SUP top STRUCTURAL MAY ( cn $ uidNumber ) )
```

sftpuser will have uid > 10000. Insert firts uid (will be 10001) 

```
uidNext.firstentry.ldif

dn: cn=uidNext,dc=example,dc=tld
objectClass: uidNext
cn: uidNext
uidNumber: 10001
```

## Installation  

Download or clone files  
Edit site-config.php filling with your data  


```php
<?php
define ("LDAP_HOST_NAME","localhost");

// The protocol version [2,3]
define ("LDAP_PROTOCOL_VERSION","3");

// The server port (To use ldapssl change to 636)
define ("LDAP_PORT","389");

// Set LDAP_TLS to 1 if you want to use TLS
define ("LDAP_TLS",0);

// The container
define ("SUFFIX","dc=example,dc=tld");

// The admin bind dn 
# Get value from function. if it doesn't work set it manually
$binddn =  get_bind_dn();
define ("BINDDN",$binddn);

// The Phamm container - change it if your installation has different structure
define ("LDAP_BASE","o=hosting," . SUFFIX);

//The People container for sftp users and vpn users
define ("LDAP_PEOPLE","ou=sshd,ou=People," .  SUFFIX);

//To create internal links
define ("BASE_PATH" , basename(__DIR__));

// The languages available
 $supported_languages = array();
// $supported_languages["de_DE"] = "Deutsch";
 $supported_languages["en_GB"] = "English";
 $supported_languages["es_ES"] = "Español";
// $supported_languages["fr_FR"] = "French";
// $supported_languages["hu_HU"] = "Hungarian";
// $supported_languages["it_IT"] = "Italiano";
// $supported_languages["pl_PL"] = "Polish";
// $supported_languages["ru_RU"] = "Russian";
// $supported_languages["vi_VN"] = "Tiếng Việt"; // Vietnamese
// $supported_languages["da_DK"] = "Dansk"; // Danish
// $supported_languages["pt_BR"] = "Portuguese";


 # Get admin name dinamically throug a ESXTERNAL bind connection
 # Ldap need to be configured in oreder to allow 
 # anonymous bind connection for apache

## Get the admin bame
    function get_bind_dn () {
      //Special connection Only read mode to rertieve cn and mail
      $host = "ldapi:///";
      $base = SUFFIX;

      $ds = ldap_connect($host);

      //buscamos cualquier entrada
      $filter="(&(objectclass=extensibleObject)(!(cn=uidNext)))";
      //de las entradas solo queremos cn y mail
      $justthese = array("cn");

      //como usuario anonimo solo tenemos acceso al primer nivel de la base de
      //datos, asi que solo tenemos acceso al dn de admin. y solo tenemos acceso
      //a su atributo cn e email.

      $sr=ldap_search($ds, $base, $filter, $justthese);
      $info = ldap_get_entries($ds, $sr);
      /*echo $info["count"]." entradas devueltas\n";
      echo "<pre>";
      print_r ($info);
      echo "</pre>";
      */
      $adminname = $info[0]["dn"];
      return $adminname;
      }





```

* change owner to  /files folder.  Set ownership to apache. eg: 

```
cd 'YOUR INSTALATION FOLDER(cpanel-ldap)'
chown -R  www-data files

```
* Place a copy of the ca.crt file for openvpn server (normally in /etc/openvpn/ca.crt) into the files directory
This is needed in order to be able to send instruccions to vpn users from the cpanel

```
cp /etc/openvpn/ca.crt YOUR-INSTALLATION-DIR/files/
```

if you want to automatically create apache vhosts and Document Root folder when a new domain is addedd, you need to
add the conjob that runs the cron/ldapsearch.sh script.  
In this example the script will run each 5 minutes.  
As root run crontab -e and add this line  


    */5 * * * * /var/www/html/YOUR INSTALATION FOLDER/cron/ldapsearch.sh

## Usage

Login :  
for cn=admin,dc=example,dc=tld use  
User: admin  
  
for email users will be their complete email  
user@mydomain.com  
<br />
for domain administrator will be the full domain  
eg: mydomain.com  
It will login user as postmaster@mydomain.com  


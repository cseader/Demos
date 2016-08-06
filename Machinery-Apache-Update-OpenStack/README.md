Machinery Upgrade SLES 11 SP4 Apache/PHP Workload to SLES 12 SP1 and Machinery build kvm image to launch in OpenStack

Machinery can help you to do an off line migration from one operating system version to another. This example shows an update of a SUSE Linux Enterprise Server 11 Service Pack 4 system with a running Apache/PHP5 webpage to SUSE Linux Enterprise Server 12 SP1 running the same workload on Apache/PHP7. SLES 11 has a 13 year lifecycle on web and scripting tools and does not get the lastest versions of those tools. Its imperitive that you update to SLES 12 SP1 to take advantage of the Web and Scripting Tools Module and have a better lifecycle.

Machinery is used to clone the original system and to validate the the manual migration of the cloned system. The cloned system can either be cloned from a virtual environment or by the use of AutoYaST. This way it is possible to do a migration while keeping the original system for later comparison and validation. It also enables you to redo the migration in case of unexpected problems. This allows for the most flexibility for your migration needs. 

The following Setup / Demo shows a real world example with Apache and PHP where you might be required to update your version of PHP because of the application needs. In this case of course its just for show and uses the PHP version checker and as you progress through the demo it shows different content based on the version of PHP you have running. In real world you may have code which will only execute on PHP7 or newer and give error results on an older version of PHP requireing you to move to a newer version. 

Lets begin.

## Requirements

* SLES 11 SP4 system installed.
* Separate machine (or virtual machine), where the new system is to be set up and tested.
* SLES 12 SP1 medium, e.g. the DVD or iso image.
* SLES 12 SP1 system running Machinery from the Advanced Systems Management Module along with kiwi and the Public Cloud Module for dependencies. 

## Steps

The instructions will refer to the original system as `$host_original` and the migrated system as `$host_migrated`. Replace them by real hostnames used for the migration.

### Setup Apache and PHP in original SLES 11 SP4 system.

1. Use the files in this github repo copied to /srv/www/htdocs for the webpage

### Clone the original SLES 11 SP4 system

1. From the Machinery system inspect the original system (SLES 11 SP4) to create a system description which contains all the information needed for replication.

  ```
  machinery inspect -x $host_original
  ```

2. -Only do this step if your using Physical systems or testing it with autoyast instead of VM cloning in VMware or KVM.
   
  Export the system description as AutoYaST profile

  ```
  machinery export-autoyast $host_target --autoyast-dir=/tmp/my_migration/
  ```

  This generated AutoYaST profile can be adapted to additional requirements (e.g. adding an additional hard disk,   network card, or user). This is described in the [AutoYaST ducumentation](http://doc.opensuse.org/projects/autoyast/).

3. Install a new system based on the AutoYaST profile

  There are different ways to install a new system via AutoYaST. They are described in the section about [Invoking the Auto-Installation Process](http://doc.opensuse.org/projects/autoyast/Invoking.html#invoking_autoinst) in the  AutoYaST documentation or/and in the [parameter description of Linuxrc](https://en.opensuse.org/SDB:Linuxrc#AutoYaST_Profile_Handling).

  Follow these instructions and install a new system using the AutoYaST profile.

4. -Do this step if your cloning a VM from VMware or KVM

  Shutdown the running system (`$host_original`)
 
  Clone the VM into a new name `$host_migrated`

### Upgrade the new installed (via AutoYaST) or cloned SLES 11 SP4 system 

1. Boot from DVD (or other source) into the SLES 12 SP1 installer
2. Select 'upgrade system'
3. Follow the system upgrade dialogues
4. check to make sure there are no left over SLES 11 SP4 packages installed.
5. Ensure Apache is selected for install and that PHP7 and Apache module for PHP7 are ready for install.
6. Upgrade it.

Now you have a system running SLES 12 SP1 on a new machine or in a VM, which should have the same functionality as the original system.

7. Once you boot it up then go into Network configuration and change the hostname and modify the NIC as desired. 
8. run through the YaST HTTP wizard to setup Apache correctly and add in the PHP7 module

Now you have a system running SLES 12 SP1 with your workload running.

9. Check that Apache shows the updated web page where the PHP7 code is executed.

### Validate the result of the migration by comparing parts of it with the original

1. Inspect the new system:
  ```
  machinery inspect -x $host_target 
  ```

  This creates a new description, which now can be compared to the original system description.

2. Compare the os version

  Run the comparison and check the parts of the system which are most relevant to you to see the changes that were   made to the system by the upgrade.

  ```
  machinery compare --scope os $host_original $host_target
  ```

  This will create the following output:

  ```
  # Operating system

  Only in '$host_original':
    Name: SUSE Linux Enterprise Server 11
    Version: 11 SP3
    Architecture: x86_64

  Only in '$host_target':
    Name: SUSE Linux Enterprise Server 12
    Version: 12
    Architecture: x86_64
  ```

  You can see that the version of the operating system was correctly upgraded.

2. Compare users and groups

  ```
  machinery compare --scope users,groups $host_original $host_target
  ```

  This will create an output like this:

  ```
  # Users

  Only in '$host_target':
    * ftpsecure (Secure FTP User, uid: 493, gid: 65534, shell: /bin/false)
    * nscd (User for nscd, uid: 494, gid: 495, shell: /sbin/nologin)
    * openslp (openslp daemon, uid: 492, gid: 2, shell: /sbin/nologin)
    * polkitd (User for polkitd, uid: 499, gid: 498, shell: /sbin/nologin)
    * rpc (user for rpcbind, uid: 497, gid: 65534, shell: /sbin/nologin)
    * rtkit (RealtimeKit, uid: 498, gid: 497, shell: /bin/false)
    * scard (Smart Card Reader, uid: 495, gid: 496, shell: /usr/sbin/nologin)
    * statd (NFS statd daemon, uid: 496, gid: 65534, shell: /sbin/nologin)

  # Groups

  Only in '$host_original':
    * mail (gid: 12)
    * maildrop (gid: 59)

  Only in '$host_target':
    * lock (gid: 54)
    * mail (gid: 12, users: postfix)
    * maildrop (gid: 59, users: postfix)
    * nscd (gid: 495)
    * polkitd (gid: 498)
    * rtkit (gid: 497)
    * scard (gid: 496)
    * systemd-journal (gid: 499)
  ```

  You can see that all SLES 11 SP3 user accounts are still there. You can also see that there are some new users and groups and that the `mail` and `maildop` groups now include the `postfix` user.


### Validate the result of the migration by comparing the full system descriptions

  ```
  machinery compare --show-all $host_original $host_target
  ```

  This will list all differences between the system. It creates a list, which for example contains all the packages with their new versions, changed configuration files and files not managed by RPM.

This gives you complete insight in what has changed by the migration and you can check that nothing unintentional happened.

### Show result in webpage

1. Launch web browser and go to IP address of $host_target to show the PHP7 code executing.

### Build a KVM image of the $host_target to launch on OpenStack

1. open shell on Machinery system and execute the machinery build process.

  ```
  machinery build -s -d $host_target
  ```

### Import KVM image into Glance

### Launch image in OpenStack




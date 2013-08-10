class repository {

  exec {'apt-get-update-pre':
    command => 'apt-get update',
    path    => '/usr/bin'
  }

  package {['python-software-properties', 'git', 'curl']:
    ensure  => installed,
    require => Exec['apt-get-update-pre']
  }

  exec { 'import-key':
    path    => ['/bin', '/usr/bin'],
    command => 'curl http://repos.servergrove.com/servergrove-ubuntu-precise/servergrove-ubuntu-precise.gpg.key | apt-key add -',
    unless  => 'apt-key list | grep servergrove-ubuntu-precise',
    require => Package['curl']
  }

  file {'servergrove.repo':
    path    => '/etc/apt/sources.list.d/servergrove.list',
    ensure  => present,
    content => 'deb http://repos.servergrove.com/servergrove-ubuntu-precise precise main',
    require => Exec['import-key']
  }

  exec {'nodejs-ppa':
    path    => '/usr/bin',
    command => 'add-apt-repository ppa:chris-lea/node.js',
    require => Package['python-software-properties']
  }

  exec {'apt-get-update-pos':
    command => 'apt-get update',
    path    => '/usr/bin',
    require => [File['servergrove.repo'], Exec['nodejs-ppa']]
  }
}

stage {pre: before => Stage[main]}
class {'repository': stage => pre}


class apache {
  package { 'apache2':
    name    => 'apache2-mpm-prefork',
    ensure  => installed
  }

  exec { 'apache2-rewrite':
    command => '/usr/sbin/a2enmod rewrite',
    unless  => '/bin/readlink -e /etc/apache2/mods-enabled/rewrite.load',
    require => Package['apache2']
  }

  file {'/etc/apache2/sites-available/default':
      source  => '/vagrant/puppet/files/site.conf',
      require => Package['apache2']
    }

  service { 'apache2':
    ensure  => running,
    require => [Exec['apache2-rewrite'], File['/etc/apache2/sites-available/default']]
  }
}

include apache


class mongo {
  package { 'mongodb': ensure => installed }
  service { 'mongodb':
    ensure  => running,
    require => Package['mongodb']
  }
}

include mongo


class php {
  package { ['php55', 'php55-mod-php', 'php55-mongo', 'php55-intl', 'php55-readline', 'libjpeg8']:
    ensure   => installed,
    notify   => Service['apache2'],
    require  => [File['servergrove.repo'], Package['apache2']],
  }
}

include php


class nodejs {

  exec {'nodejs':
    path    => ['/usr/bin', '/bin', '/sbin'],
    command => 'apt-get install nodejs -y --force-yes',
    creates => '/usr/bin/nodejs'
  }

  exec {'uglifycss':
    path    => '/usr/bin',
    command => 'npm install -g uglifycss',
    require => Exec['nodejs'],
    creates => '/usr/bin/uglifycss'
  }

  exec {'uglifyjs':
    path    => '/usr/bin',
    command => 'npm install -g uglify-js',
    require => Exec['nodejs'],
    creates => '/usr/bin/uglifyjs'
  }
}

include nodejs

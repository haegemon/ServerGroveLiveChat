class repository {

  package { 'curl': ensure => installed }

  exec { 'import-key':
    path    => ['/bin', '/usr/bin'],
    command => 'curl http://repos.servergrove.com/servergrove-ubuntu-precise/servergrove-ubuntu-precise.gpg.key | apt-key add -',
    unless  => 'apt-key list | grep servergrove-ubuntu-precise',
    require => Package['curl']
  }

  file { 'servergrove.repo':
    path    => '/etc/apt/sources.list.d/servergrove.list',
    ensure  => present,
    content => 'deb http://repos.servergrove.com/servergrove-ubuntu-precise precise main',
    require => Exec['import-key']
  }

  exec { 'apt-get-update':
    command => 'apt-get update',
    path    => ['/bin', '/usr/bin'],
    require => File['servergrove.repo']
  }
}

stage { pre: before => Stage[main] }


class { 'repository':
  stage => pre
}


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

  service { 'apache2':
    ensure  => running,
    require => Exec['apache2-rewrite']
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


class webserver {
  file { '/etc/apache2/sites-available/default':
    source  => '/vagrant/puppet/files/site.conf',
    notify  => Service['apache2'],
    require => Package['apache2']
  }
}

include webserver


class git {
  package { 'git':
    ensure => installed
  }
}

include git


class servergrovelivechat {

  exec { 'composer-install':
    path    => ['/usr/bin', '/usr/local/php55/bin'],
    command => 'curl -sS https://getcomposer.org/installer | php',
    cwd     => '/vagrant',
    creates => '/vagrant/composer.phar',
    require => [Package['php55'], Package['curl']]
  }

  exec { 'composer-dependencies':
    path    => '/usr/local/php55/bin',
    command => 'php composer.phar install --dev',
    cwd     => '/vagrant',
    creates => '/vagrant/vendor',
    require => [Package['git'], Exec['composer-install']],
    timeout => 3600
  }
}

include servergrovelivechat
#!/usr/bin/perl

# i-MSCP - internet Multi Server Control Panel
# Copyright (C) 2010 - 2011 by internet Multi Server Control Panel
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
#
# @category		i-MSCP
# @copyright	2010 - 2012 by i-MSCP | http://i-mscp.net
# @author		Daniel Andreca <sci2tech@gmail.com>
# @version		SVN: $Id$
# @link			http://i-mscp.net i-MSCP Home Site
# @license		http://www.gnu.org/licenses/gpl-2.0.html GPL v2

package Addons::piwik::installer;

use strict;
use warnings;
use Digest::MD5 qw(md5_hex);
use iMSCP::Debug;
use Data::Dumper;

use vars qw/@ISA/;

@ISA = ('Common::SingletonClass');
use Common::SingletonClass;

sub _init{

	my $self		= shift;
	$self->{cfgDir}	= "$main::imscpConfig{'CONF_DIR'}/piwik";
	$self->{bkpDir}	= "$self->{cfgDir}/backup";
	$self->{wrkDir}	= "$self->{cfgDir}/working";

	my $conf		= "$self->{cfgDir}/piwik.data";
	my $oldConf		= "$self->{cfgDir}/piwik.old.data";

	tie %self::piwikConfig, 'iMSCP::Config','fileName' => $conf, noerror => 1;
	tie %self::piwikOldConfig, 'iMSCP::Config','fileName' => $oldConf, noerror => 1 if -f $oldConf;

	0;
}

sub install{

	my $self	= shift;
	my $rs		= 0;
	$self->{httpd} = Servers::httpd->factory() unless $self->{httpd} ;

	$self->{user} = $self->{httpd}->can('getRunningUser') ? $self->{httpd}->getRunningUser() : $main::imscpConfig{ROOT_USER};
	$self->{group} = $self->{httpd}->can('getRunningUser') ? $self->{httpd}->getRunningGroup() : $main::imscpConfig{ROOT_GROUP};

	$rs |= $self->bkpConfFile("$main::imscpConfig{'GUI_PUBLIC_DIR'}/$self::piwikConfig{'PIWIK_CONF_DIR'}/config.ini.php");

	$rs |= $self->chmodDirs($_);
	$rs |= $self->setupDB();
	$rs |= $self->superuserpw();
	$rs |= $self->buildConf();
	$rs |= $self->saveConf();

	$rs;
}

sub saveConf{

	use iMSCP::File;

	my $self	= shift;
	my $rootUsr	= $main::imscpConfig{'ROOT_USER'};
	my $rootGrp	= $main::imscpConfig{'ROOT_GROUP'};
	my $rs		= 0;

	my $file	= iMSCP::File->new(filename => "$self->{cfgDir}/piwik.data");
	my $cfg		= $file->get();
	return 1 unless $cfg;
	$rs			|= $file->mode(0640);
	$rs			|= $file->owner($rootUsr, $rootGrp);

	$file	= iMSCP::File->new(filename => "$self->{cfgDir}/piwik.old.data");
	$rs		|= $file->set($cfg);
	$rs		|= $file->save();
	$rs		|= $file->mode(0640);
	$rs		|= $file->owner($rootUsr, $rootGrp);

	$rs;
}

sub bkpConfFile{

	use File::Basename;

	my $self		= shift;
	my $cfgFile		= shift;
	my $timestamp	= time;

	my ($name,$path,$suffix) = fileparse($cfgFile,);

	if(-f $cfgFile){
		my $file	= iMSCP::File->new(filename => $cfgFile);
		$file->copyFile("$self->{bkpDir}/$name$suffix.$timestamp") and return 1;
	}

	0;
}

################################################################################
# Creating piwik database
#
# @return int 0 on success, other on failure
#
sub createDB{
	my $dbName = shift;
	my $dbType = shift;

	use iMSCP::Database;

        my $database = iMSCP::Database->new(db => $dbType)->factory();
        $database->set('DATABASE_NAME', '');
        my $err = $database->connect();
        return $err if $err;

        my $qdbName = $database->quoteIdentifier($dbName);
        $err = $database->doQuery('dummy', "CREATE DATABASE ".$self::piwikConfig{'DATABASE_NAME'}." CHARACTER SET utf8 COLLATE utf8_unicode_ci;");

        $database->set('DATABASE_NAME', $self::piwikConfig{'DATABASE_NAME'});
        $err = $database->connect();
        return $err if $err;

        $err = importSQLFile($database, "$main::imscpConfig{'CONF_DIR'}/database/piwik.sql");
        return $err if ($err);

	## We ensure that new data doesn't exist in database
	$err = $database->doQuery(
		'dummy',"
			DELETE FROM `mysql`.`tables_priv`
			WHERE `Host` = ?
			AND `Db` = 'mysql' AND `User` = ?;
		", $main::imscpConfig{'DATABASE_HOST'}, $self::piwikConfig{'DATABASE_USER'}
	);
	if (ref $err ne 'HASH'){
		error("$err");
		return 1;
	}

	$err = $database->doQuery(
		'dummy',"
			DELETE FROM `mysql`.`user`
			WHERE `Host` = ?
			AND `User` = ?;
		", $main::imscpConfig{'DATABASE_HOST'}, $self::piwikConfig{'DATABASE_USER'}
	);
	if (ref $err ne 'HASH'){
		error("$err");
		return 1;
	}

	$err = $database->doQuery(
		'dummy',"
			DELETE FROM `mysql`.`columns_priv`
			WHERE `Host` = ?
			AND `User` = ?;
		", $main::imscpConfig{'DATABASE_HOST'}, $self::piwikConfig{'DATABASE_USER'}
	);
	if (ref $err ne 'HASH'){
		error("$err");
		return 1;
	}
	# Flushing privileges
	$err = $database->doQuery('dummy', 'FLUSH PRIVILEGES');
	if (ref $err ne 'HASH'){
		error("$err");
		return 1;
	}
	## GRANT database permsions to piwik user
	$err = $database->doQuery(
		'dummy',
		"
			GRANT ALL PRIVILEGES ON `".$main::imscpConfig{DATABASE_NAME}.'_piwik'."`.*
			TO ?@?
			IDENTIFIED BY ?;
		",
		$self::piwikConfig{'DATABASE_USER'},
		$main::imscpConfig{'DATABASE_HOST'},
		$self::piwikConfig{'DATABASE_PASSWORD'}
	);
	if (ref $err ne 'HASH'){
		error("$err");
		return 1;
	}
	## CREATE the database and load the default schema
	##TODO

	## INSERT the default anonymous user, required start
	$err = $database->doQuery(
		'dummy',
		"
			REPLACE INTO `".$main::imscpConfig{DATABASE_NAME}.'_piwik`'.".`user` 
			(`login` ,`password` ,`alias` ,`email` ,`token_auth` ,`date_registered`)
			VALUES ('anonymous', '', '', '', '', NOW( ));
		"
	);
	if (ref $err ne 'HASH'){
		error("$err");
		return 1;
	}
}

sub setupDB{

	my $self		= shift;
	my $connData;

	#recover the piwik db username and password from config or ask for a new one
	if(!$self->check_sql_connection
		(
			$self::piwikConfig{'DATABASE_USER'} || '',
			$self::piwikConfig{'DATABASE_PASSWORD'} || '',
			$self::piwikConfig{'DATABASE_NAME'} || ''
		)
	){
		$connData = 'yes';
	}elsif($self::piwikOldConfig{'DATABASE_USER'} && !$self->check_sql_connection
		(
			$self::piwikOldConfig{'DATABASE_USER'} || '',
			$self::piwikOldConfig{'DATABASE_PASSWORD'} || '',
			$self::piwikConfig{'DATABASE_NAME'} || ''
		)
	){
		$self::piwikConfig{'DATABASE_USER'}		= $self::piwikOldConfig{'DATABASE_USER'};
		$self::piwikConfig{'DATABASE_PASSWORD'}	= $self::piwikOldConfig{'DATABASE_PASSWORD'};
		$self::piwikConfig{'DATABASE_NAME'}	= $self::piwikOldConfig{'DATABASE_NAME'};
		$connData = 'yes';
	} else {
		my $dbUser = 'piwik_user';

		do{
			$dbUser = iMSCP::Dialog->factory()->inputbox("Please enter database user name for the restricted piwik user (default piwik_user)", $dbUser);
			#we will not allow root user to be used as database user for dovecot since account will be restricted
			if($dbUser eq $main::imscpConfig{DATABASE_USER}){
				iMSCP::Dialog->factory()->msgbox("You can not use $main::imscpConfig{DATABASE_USER} as restricted user");
				$dbUser = undef;
			}
		} while (!$dbUser);

		iMSCP::Dialog->factory()->set('cancel-label','Autogenerate');
		my $dbPass;
		$dbPass = iMSCP::Dialog->factory()->inputbox("Please enter database password (leave blank for autogenerate)", $dbPass);
		if(!$dbPass){
			$dbPass = '';
			my @allowedChars = ('A'..'Z', 'a'..'z', '0'..'9', '_');
			$dbPass .= $allowedChars[rand()*($#allowedChars + 1)] for (1..16);
		}
		$dbPass =~ s/('|"|`|#|;|\/|\s|\||<|\?|\\)/_/g;
		iMSCP::Dialog->factory()->msgbox("Your password is '".$dbPass."' (we have stripped not allowed chars)");
		iMSCP::Dialog->factory()->set('cancel-label');
		$self::piwikConfig{'DATABASE_USER'}		= $dbUser;
		$self::piwikConfig{'DATABASE_PASSWORD'}	= $dbPass;
		$self::piwikConfig{'DATABASE_NAME'}	= $main::imscpConfig{DATABASE_NAME}.'_piwik';
	}

	#restore db connection
	my $crypt = iMSCP::Crypt->new();
	my $err = $self->check_sql_connection(
			$main::imscpConfig{'DATABASE_USER'},
			$main::imscpConfig{'DATABASE_PASSWORD'} ? $crypt->decrypt_db_password($main::imscpConfig{'DATABASE_PASSWORD'}) : ''
	);

	if ($err){
		error("$err");
		return 1;
	}
	
	if(!$connData) {
		my $database = iMSCP::Database->new(db => $main::imscpConfig{DATABASE_TYPE})->factory();

		## Is the database already created?
		$err = $database->doQuery(
			'dummy',"
				SHOW DATABASES LIKE '".$main::imscpConfig{DATABASE_NAME}.'_piwik'."'
			"
		);
		if (ref $err ne 'HASH'){
			error("$err");
			return 1;
		} else {
			local $Data::Dumper::Terse = 1;
			debug("Data: ". (Dumper $err));
			if ($err) {
				#piwik database not present, create
				debug("Piwik was not present, installing: ". (Dumper $err));
				if (my $err = createDB($self::piwikConfig{'DATABASE_NAME'}, $main::imscpConfig{'DATABASE_TYPE'})){
					error("$err");
					return 1;
				}
			} else {
				#piwik database present, upgrade
				debug("Piwik present present, updating: ". (Dumper $err));
				if (my $err = updateDB($self::piwikConfig{'DATABASE_NAME'})){
					error("$err");
					return 1;
				}
			}
		}
	}
	0;
}

sub check_sql_connection{

	use iMSCP::Database;

	my ($self, $dbUser, $dbPass) = (@_);
	my $database = iMSCP::Database->new(db => $main::imscpConfig{DATABASE_TYPE})->factory();
	$database->set('DATABASE_USER',		$dbUser);
	$database->set('DATABASE_PASSWORD',	$dbPass);

	return $database->connect();
}

sub importSQLFile{
	my $database	= shift;
	my $file		= shift;

	use iMSCP::File;
	use iMSCP::Dialog;
	use iMSCP::Stepper;

	my $content = iMSCP::File->new(filename => $file)->get();
	$content =~ s/^(--[^\n]{0,})?\n//mg;
	my @queries = (split /;\n/, $content);

	my $title = "Executing ".@queries." queries:";

	startDetail();

	my $step = 1;
	for (@queries){
		my $err = $database->doQuery('dummy', $_);
		return $err if (ref $err ne 'HASH');
		my $msg = $queries[$step] ? "$title\n$queries[$step]" : $title;
		step('', $msg, scalar @queries, $step);
		$step++;
	}

	endDetail();
	0;
}

sub superuserpw{

	my $self = shift;

	$self::piwikConfig{'SUPERUSERPW'} = $self::piwikOldConfig{'SUPERUSERPW'}
		if(!$self::piwikConfig{'SUPERUSERPW'} && $self::piwikOldConfig{'SUPERUSERPW'});

	unless($self::piwikConfig{'SUPERUSERPW'}){
		my $superuserpw = '';
		my @allowedChars = ('A'..'Z', 'a'..'z', '0'..'9', '_');
		$superuserpw .= $allowedChars[rand()*($#allowedChars + 1)] for (1..24);
		$self::piwikConfig{'SUPERUSERPW'} = $superuserpw;
	}

	0;
}


sub buildConf{

	use Servers::mta;

	my $self		= shift;
	my $panelUName	= $main::imscpConfig{'SYSTEM_USER_PREFIX'}.$main::imscpConfig{'SYSTEM_USER_MIN_UID'};
	my $panelGName	= $main::imscpConfig{'SYSTEM_USER_PREFIX'}.$main::imscpConfig{'SYSTEM_USER_MIN_UID'};
	my $rs			= 0;


	my $cfg = {
		DB_HOST				=> $main::imscpConfig{DATABASE_HOST},
		DB_USER				=> $self::piwikConfig{DATABASE_USER},
		DB_PASS				=> $self::piwikConfig{DATABASE_PASSWORD},
		DB_NAME				=> $main::imscpConfig{DATABASE_NAME}.'_piwik',
		DEFAULT_ADMIN_ADDRESS		=> $main::imscpConfig{DEFAULT_ADMIN_ADDRESS},
		SUPERUSERMD5			=> md5_hex($self::piwikConfig{SUPERUSERPW}),
	};

	my $cfgFiles = {
		'config.ini.php'		=> "$main::imscpConfig{'GUI_PUBLIC_DIR'}/$self::piwikConfig{'PIWIK_CONF_DIR'}/config.ini.php",
	};

	for (keys %{$cfgFiles}) {
		my $file	= iMSCP::File->new(filename => "$self->{cfgDir}/$_");
		my $cfgTpl	= $file->get();
		if (!$cfgTpl){
			$rs = 1;
			next;
		}

		$cfgTpl = iMSCP::Templator::process($cfg, $cfgTpl);
		if (!$cfgTpl){
			$rs = 1;
			next;
		}

		$file = iMSCP::File->new(filename => "$self->{wrkDir}/$_");
		$rs |= $file->set($cfgTpl);
		$rs |= $file->save();
		$rs |= $file->mode(0640);
		$rs |= $file->owner($panelUName, $panelGName);
		$rs |= $file->copyFile($cfgFiles->{$_});
	}

	0;
}

sub chmodDirs{

	use iMSCP::Dir;

	my $self		= shift;

	iMSCP::Dir->new(
		dirname => "$main::imscpConfig{'GUI_PUBLIC_DIR'}/tools/stats/tmp/"
	)->make({
		mode => 0755
	}) and return 1;

	0;
}


1;
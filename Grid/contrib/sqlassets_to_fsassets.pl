#!/usr/bin/perl
# ===========================================
# author     Dave Coyle <http://coyled.com>
# copyright  Open Metaverse Foundation
# license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
# link       http://openmetaverse.googlecode.com/
# ===========================================
#
# pull asset data out of MySQL and stick in file on local filesystem.  Useful
# if you want to migrate from SQLAssets to FSAssets.
#
# usage: ./sqlassets_to_fsassets.pl --db-host=foo --db-name=bar
#           --db-user=baz --asset-dir=/path/to/converted/assets
#
# installation instructions:
#       ubuntu / debian:
#              - aptitude install perl libterm-readkey-perl libdbd-mysql-perl libdbi-perl
#              - then run 
#
# no idea if this works on Windows.  sorry.
#

use warnings;
use strict;
use Getopt::Long;
#use MIME::Base64;
use DBI;
use Term::ReadKey;
use File::Path qw(make_path);

my ($db_host, $db_name, $db_user, $asset_dir);
GetOptions(
    'db-host=s'         =>  \$db_host,
    'db-name=s'         =>  \$db_name,
    'db-user=s'         =>  \$db_user,
    'asset-dir=s'       =>  \$asset_dir,
);
if (!$db_host || !$db_name || !$db_user || !$asset_dir) { show_help(); }

# prompt for db password...
print STDERR "Enter MySQL password: ";
ReadMode('noecho');
my $db_pass = ReadLine(0);
ReadMode('normal');
chop $db_pass;
print STDERR "\n\n";

# set up connection to mysql...
my ($dbh, $sql, $sth);
$dbh = DBI->connect ("DBI:mysql:database=$db_name:host=$db_host",
    $db_user, $db_pass )
    or die "can't connect to db.  $DBI::errstr\n";

$sql = "select SHA256,Data from AssetData;";
$sth = $dbh->prepare($sql);
$sth->execute();
while (my @results = $sth->fetchrow_array()) {
    my ($asset_hash, $asset_data) = @results;
    my $asset_dir = $asset_dir . "/" .
        substr($asset_hash, 0, 2) . "/" .
        substr($asset_hash, 2, 2);
    if (! -d $asset_dir) {
        make_path($asset_dir, 1, { mode => 0755 })
            or die "couldn't mkdir $asset_dir\n";
    }
    open(ASSET, ">$asset_dir/$asset_hash");
    print ASSET $asset_data;
    close(ASSET);
}
$sth->finish;

$dbh->disconnect;

sub show_help {
    print "usage: ./sqlassets_to_fsassets.pl --db-host=<hostname> --db-user=<username> --db-name=<db_name> --asset-dir=<path_to_converted_assets>\n";
    exit 1;
}

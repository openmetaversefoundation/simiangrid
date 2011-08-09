#!/usr/bin/perl
# ===========================================
# author     Dave Coyle <http://coyled.com>
# copyright  Open Metaverse Foundation
# license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
# link       http://openmetaverse.googlecode.com/
# ===========================================
#
# find and print assets which have incorrect entries in the SHA256
# column in the db.
#
# usage: ./find_incorrect_asset_hashes.pl --db-host=foo --db-name=bar \
#               --db-user=baz
#
# installation instructions:
#       ubuntu / debian:
#              - aptitude install perl libterm-readkey-perl \
#                   libdbd-mysql-perl libdbi-perl 
#              - then run 
#
# no idea if this works on Windows.  sorry.
#

use warnings;
use strict;
use Getopt::Long;
use DBI;
use Term::ReadKey;
use Digest::SHA 'sha256_hex';

my ($db_host, $db_name, $db_user);
GetOptions(
    'db-host=s'         =>  \$db_host,
    'db-name=s'         =>  \$db_name,
    'db-user=s'         =>  \$db_user,
);
if (!$db_host || !$db_name || !$db_user) { show_help(); }

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

$sql = "select ID,SHA256,Data from AssetData;";
$sth = $dbh->prepare($sql);
$sth->execute();
while (my @results = $sth->fetchrow_array()) {
    my ($asset_id, $asset_hash, $asset_data) = @results;
    my $correct_hash = sha256_hex($asset_data);
    if ($asset_hash ne $correct_hash) {
        print "Asset ID: $asset_id\n";
        print "    Hash in db:   $asset_hash\n";
        print "    Correct hash: $correct_hash\n";
        print "---------------------------------\n";
    }
}
$sth->finish;

$dbh->disconnect;

sub show_help {
    print "usage: ./find_incorrect_asset_hashes.pl --db-host=<hostname> --db-user=<username> --db-name=<db_name>\n";
    exit 1;
}

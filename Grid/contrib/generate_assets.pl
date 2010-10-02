#!/usr/bin/perl
# ===========================================
# author     Dave Coyle <http://coyled.com>
# copyright  Open Metaverse Foundation
# license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
# link       http://openmetaverse.googlecode.com/
# ===========================================
#
# generate fake 4KB assets and shove them into SimianGrid
#
# usage: ./generate_assets --count 100 --asset-url http://grid.example.com/Grid/
#
# installation instructions:
#       ubuntu / debian:
#              - aptitude install perl libwww-perl 
#              - then run 
#
# no idea if this works on Windows.  sorry.
#

use warnings;
use strict;
use Getopt::Long;
use LWP::UserAgent;
use HTTP::Request::Common;
use MIME::Base64;

# get command line options...
my ($number_to_create, $asset_server_url);
GetOptions(
    'count=i'       =>  \$number_to_create,
    'asset-url=s'   =>  \$asset_server_url,
);

# bail if missing options...
unless ($number_to_create && $asset_server_url) {
    print "usage:  ./generate_assets --count 100 --asset-url http://grid.example.com/Grid/\n";
    exit 1;
}

# let's make fake lsl scripts...
my $asset_type = 'application/x-metaverse-lsl';

# we're gonna generate some random text.  set the list of allowed
# chars...
my @chars = ('a'..'z', 'A'..'Z', '0'..'9');

for (1..$number_to_create) {
    # number of 1KB blocks we want this file to be...
    my $block_size = 4;

    # generate fake LSL script of desired size...
    my $rand_lsl;
    for (1..(16*$block_size)) {
        $rand_lsl .= '\\\\ ';
        for (1..60) {
            $rand_lsl .= $chars[rand @chars];
        }
        $rand_lsl .= "\n";
    }

    my $asset_data = encode_base64($rand_lsl);

    my $ua = LWP::UserAgent->new;
    my $response = $ua->request(POST $asset_server_url,
        Content_Type => 'form-data',
        Content =>  [
            Asset   =>  [ undef, 'fakeasset.lsl', Content => $asset_data ]
        ]
    );

    print "response: " . $response->status_line . " -- " . $response->decoded_content . "\n";
}

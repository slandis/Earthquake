#!/usr/bin/perl -w

use strict;
use HTML::TableExtract;
use LWP::Simple;

my @urls = ("http://http://earthquake.usgs.gov/earthquakes/recenteqsww/Maps/region/Africa_eqs.php",
         "http://http://earthquake.usgs.gov/earthquakes/recenteqsww/Maps/region/Asia.php",
         "http://earthquake.usgs.gov/earthquakes/recenteqsww/Maps/region/Australia_eqs.php",
         "http://http://earthquake.usgs.gov/earthquakes/recenteqsww/Maps/region/Europe_eqs.php",
         "http://earthquake.usgs.gov/earthquakes/recenteqsww/Maps/region/N_America_eqs.php",
         "http://earthquake.usgs.gov/earthquakes/recenteqsww/Maps/region/S_America_eqs.php",
         "http://earthquake.usgs.gov/earthquakes/recenteqsww/Maps/region/S_Pacific_eqs.php");

foreach my $url (@urls) {
  my $html = get($url);
  my $te = new HTML::TableExtract( depth => 0, decode => 0, keep_headers => 0);
  $te->parse($html);

  foreach my $ts ($te->tables) {
    foreach my $row ($ts->rows) {
      my $line = join('|', @$row) . "\n";
      $line =~ s/&nbsp;//g;
      
      if ($line =~ /^MAP/) {
        print $line;
      }
    }
  }
}

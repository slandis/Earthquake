#!/usr/bin/perl -w

use Date::Parse;

open(DATA, "<month");

my $i = 1;

while (my $line = <DATA>) {
  my ($src,$eqid,$version,$time,$lat,$lon,$mag,$depth,$nst,$region) = ($line =~ /(".*?"|,)/g);
  $time = str2time($time);
  print $i++ . "|$map|$mag|$time|$lat|$lon|$depth|$region";
}

close(DATA);

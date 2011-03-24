#!/usr/bin/python

import freebase

query = {
    "id" :   "/en/nebula_award",
    "type" : "/award/award",
    "category" : [{
      "name" : None,
      "nominees" : [{
        "award_nominee" : [{
          "name" : None,
        }],
        "nominated_for" : [{
          "name" : None,
        }],
        "limit" : 5000,
      }]
    }]
}

results = freebase.mqlread(query)

for category in results.category:
    print "Category: " + category.name
    for nominee in category.nominees:
        print " %s - %s" % (nominee.award_nominee[0].name, nominee.nominated_for[0].name)

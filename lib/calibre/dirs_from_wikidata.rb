#!/usr/bin/env ruby
# coding: utf-8

require 'pry'

require 'ostruct'
require 'optparse'
require 'net/http'
require 'json'

# http://andreapavoni.com/blog/2013/4/create-recursive-openstruct-from-a-ruby-hash/
class DeepStruct < OpenStruct
  def initialize(hash = nil)
    if hash
      @table = {}
      @hash_table = {}
      
      hash.each do |k, v|
        @table[k.to_sym] = (v.is_a?(Hash) ? self.class.new(v) : v)
        @hash_table[k.to_sym] = v
        new_ostruct_member(k)
      end
    end
  end

  def to_h; @hash_table; end
end

opts = DeepStruct.new({
  src: { base: '.../award/Hugo/year/' },
  dest: { base: '.../book/by/' },
  query: {
    base: 'https://query.wikidata.org/sparql?format=json&query=',
    sparql: """
SELECT DISTINCT ?itemLabel ?authorLabel ?when
 WHERE {
  ?award ps:P166 wd:Q255032; pq:P585 ?when.
  ?item p:P166 ?award; wdt:P50 ?author.
  SERVICE wikibase:label { bd:serviceParam wikibase:language 'en' }
 }
"""
  }
})

OptionParser.new do |options|
  options.banner = "Usage: #{$0} [-n]"
  options.on('-n', '--dry-run') { opts.dry = true }
end.parse!

query_url = "#{opts.query.base}#{opts.query.sparql}"
puts "Querying: #{query_url}\n"

ret = Net::HTTP.get(URI.parse(query_url))
source = JSON.parse(ret, symbolize_names: true)

if not File.exist?(opts.src.base)
  puts "Creating #{opts.src.base}"
  FileUtils.mkdir_p opts.src.base if not opts.dry
end

source[:results][:bindings].each do |entry|
  entry = DeepStruct.new(entry.each { |k, v| entry[k] = v[:value] })

  year = entry.when.match(/([^-]+)-/)[1]
  item = "#{opts.src.base}#{year}"
  dest = "#{opts.dest.base}#{entry.authorLabel}/#{entry.itemLabel}"

  if not File.exist?(dest) and false
    puts "Creating #{dest}"
    FileUtils.mkdir_p dest if not opts.dry
  end

  if File.exists?(item) or File.symlink?(item)
    puts "Skipping (#{dest}):"
    puts " Skip: Exists: #{item}"
  else
    puts "Linking: '#{item}' → '#{dest}"
    FileUtils.ln_s dest, item if not opts.dry
  end
end

#binding.pry

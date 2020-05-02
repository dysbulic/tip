#!/usr/bin/env ruby
# coding: utf-8

require 'ostruct'
require 'optparse'
require 'pry'
require 'fileutils'

options = OpenStruct.new()
OptionParser.new do |opts|
  opts.banner = "Usage: fix_pics.rb [-n]"
  opts.on('-n', '--dry-run') { options.dry = true }
end.parse!

def size(dir)
  if File.directory?(dir)
    Dir.glob(File.join(dir, '**', '*')).map do |file|
      File.exists?(file) ? File.size(file) : 0
    end.inject(0, :+)
  else
    File.size(dir)
  end
end

Dir.entries('.').each do |file|
  new = nil
  file = file.force_encoding('utf-8')

  puts "Processing: #{file}"
  
  if File.directory?(file)
    if Dir.entries(file).count == 2
      Dir::rmdir(file) if not options.dry
      puts " Removed empty directory: '#{file}'"
    elsif split = /^(?<author>.*) - (?<title>.*)$/.match(file)
      new = "by/#{split[:author]}/#{split[:title]}"
    end
  end

  if new
    if File.exists?(new)
      if (size = size(file)) == size(new)
        puts " Directory Duplicated: '#{new}' (#{size})"
        FileUtils.rm_rf(file) if not options.dry
      else
        puts " Conflict: '#{file}' & '#{new}'"
      end
    else
      puts " Renaming: '#{file}' → '#{new}'"
      FileUtils.mkdir_p(new) if not options.dry
      FileUtils.mv(Dir.glob(File.join(file, '**', '*'), new)) if not options.dry
    end
  end
end

Dir.glob('by/*/*/*.*').each do |file|
  puts "Processing #{file}"

  if match = /by\/(?<dirauth>.*)\/(?<dirtitle>.*)\/(?<fileauth>.*) - (?<filetitle>.*)\.(?<ext>[^\.]*)$/.match(file)
    if match[:dirtitle] == match[:filetitle] && match[:dirauth] == match[:fileauth]
      new = "by/#{match[:dirauth]}/#{match[:dirtitle]}/#{match[:ext]}"
      puts " Renaming: #{file} → #{new}"
      FileUtils.mv(file, new) if not options.dry
    end
  end
end

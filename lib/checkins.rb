# frozen_string_literal: true

require "rss"
require "open-uri"

File.delete("data/checkins.yml") if File.exist?("data/checkins.yml")

url = "https://untappd.com/rss/brewery/371606"
open(url) do |rss|
  feed = RSS::Parser.parse(rss)

  File.open("data/checkins.yml", "w") do |f|
    feed.items[0..6].each do |item|
      f.write("- title: \"#{item.title}\"\n")
      f.write("\s\sdescription: \"#{item.description}\"\n")
      f.write("\s\slink: \"#{item.description}\"\n")
      f.write("\s\spubdate: \"#{item.pubDate}\"\n")
    end
    f.close
  end
end

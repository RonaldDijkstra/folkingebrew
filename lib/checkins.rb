# frozen_string_literal: true

require "rss"
require "open-uri"

File.delete("data/checkins.yml") if File.exist?("data/checkins.yml")

url = "https://untappd.com/rss/brewery/371606"
open(url) do |rss|
  feed = RSS::Parser.parse(rss)

  File.open("data/checkins.yml", "w") do |f|
    feed.items[0..3].each do |item|
      f.write("- title: \"#{item.title}\"\n")
      f.write("\s\sdescription: \"#{item.description}\"\n")
      f.write("\s\slink: \"#{item.description}\"\n")
      f.write("\s\spubdate: \"#{item.pubDate}\"\n")
      if item.title.include? "Pastry Project #1"
        f.write("\s\simage: https://labels.untappd.com/labels/3159580?size=hd\"\n")
      elsif item.title.include? "Back in the Yard DIPA"
        f.write("\s\simage: https://labels.untappd.com/labels/3050712?size=hd\"\n")
      else
        f.write("\s\simage: https://labels.untappd.com/labels/2404100?size=hd\"\n")
      end
    end
    f.close
  end
end

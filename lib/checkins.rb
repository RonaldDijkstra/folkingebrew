# frozen_string_literal: true

require "open-uri"
require "nokogiri"
require "json"
require_relative "colorizer"

begin
  puts "== Trying to fetch checkins..."
  url = "https://untappd.com/Folkingebrew"
  doc = Nokogiri::HTML(open(url))
  checkins = doc.css("#main-stream .item")

  puts "== Fetching checkins succeeded, writing checkins.yml".green

  File.open("data/checkins.yml", "w") do |f|
    checkins.each do |checkin|
      next unless checkin.css("p.photo img").attr("data-original")

      user = checkin.at(".text .user").text
      f.write("- user: \"#{user}\"\n")

      title = checkin.at(".text").text.strip.gsub(/\s+/, " ")
      f.write("\s\stitle: \"#{title}\"\n")

      checkin_image = checkin.css("p.photo img").attr("data-original")
      f.write("\s\simage: \"#{checkin_image}\"\n")

      date_time = checkin.at(".time").text
      date = Time.parse(date_time)
      f.write("\s\sdate: \"#{date.day}-#{date.month}-#{date.year}\"\n")
    end
    puts "== Writing checkins.yml completed".green
  end
rescue OpenURI::HTTPError => e
  puts "== Fetching checkins failed due to #{e}".red
  puts "== Skipping writing checkins.yml and using old file instead".red
end

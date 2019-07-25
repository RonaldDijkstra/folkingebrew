# frozen_string_literal: true

require "open-uri"
require "nokogiri"
require "json"
require_relative "colorizer"

begin
  io_thing = open("https://untappd.com/Folkingebrew")

  # The text of the status code is in [1]
  the_status = io_thing.status[0]

rescue OpenURI::HTTPError => e
  # some clean up work goes here and then..

  the_status = e.io.status[0] # => 3xx, 4xx, or 5xx

  # the_error.message is the numeric code and text in a string
  puts "== Error Retrieving Checkins: got a bad status code #{e.message}".red
end

if the_status == "200"

  puts "== Status code 200, Retrieving Checkins".green

  File.delete("data/checkins.yml") if File.exist?("data/checkins.yml")

  doc = Nokogiri::HTML(open("https://untappd.com/Folkingebrew"))

  checkins = doc.css("#main-stream .item")

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
  end
end

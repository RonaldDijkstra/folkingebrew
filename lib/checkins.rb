# frozen_string_literal: true

require "open-uri"
require "nokogiri"
require "json"

File.delete("data/checkins.yml") if File.exist?("data/checkins.yml")

doc = Nokogiri::HTML(open("https://untappd.com/Folkingebrew"))

checkins = doc.css("#main-stream .item")

File.open("data/checkins.yml", "w") do |f|
  checkins.each do |checkin|
    if checkin.css("p.photo img").attr("data-original")
      title = checkin.at(".text").text.strip.gsub(/\s+/, " ")
      f.write("- title: \"#{title}\"\n")

      checkin_image = checkin.css("p.photo img").attr("data-original")
      f.write("\s\simage: \"#{checkin_image}\"\n")

      date_time = checkin.at(".time").text
      date = Time.parse(date_time)
      f.write("\s\sdate: \"#{date.day} #{date.strftime('%B')} #{date.year}\"\n")
    end
  end
end

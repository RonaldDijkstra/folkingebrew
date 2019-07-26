# frozen_string_literal: true

require "open-uri"
require "nokogiri"
require "json"

begin
  url = "https://untappd.com/Folkingebrew"
  doc = Nokogiri::HTML(open(url))
  checkins = doc.css("#main-stream .item")

  puts "= Retrieving checkins succeeded, writing checkins.yml"

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

rescue OpenURI::HTTPError => e

  puts "Retrieving data failed due to #{e}"

end

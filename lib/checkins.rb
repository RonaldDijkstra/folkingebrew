# frozen_string_literal: true

require "open-uri"
require "nokogiri"
require "json"

File.delete("data/checkins.yml") if File.exist?("data/checkins.yml")

doc = Nokogiri::HTML(open("https://untappd.com/Folkingebrew"))

checkins = doc.css("#main-stream .item")

File.open("data/checkins.yml", "w") do |f|
  checkins.each do |checkin|
    title = checkin.at(".text").text.strip.gsub(/\s+/, " ")
    f.write("- title: \"#{title}\"\n")

    if checkin.css("p.photo img").attr("data-original")
      checkin_image = checkin.css("p.photo img").attr("data-original")
      f.write("\s\simage: \"#{checkin_image}\"\n")
    elsif title.include? "Pastry Project #1"
      f.write("\s\simage: \"https://labels.untappd.com/labels/3159580?size=hd\"\n")
    elsif title.include? "Back in the Yard DIPA"
      f.write("\s\simage: \"https://labels.untappd.com/labels/3050712?size=hd\"\n")
    elsif title.include? "Just a Backyard IPA"
      f.write("\s\simage: \"https://labels.untappd.com/labels/2404100?size=hd\"\n")
    end

    if checkin.at("p.comment-text")
      checkin_comment = checkin.at("p.comment-text").text.strip.gsub(/\s+/, " ")
      f.write("\s\scomment: \"#{checkin_comment}\"\n")
    end

    if checkin.at(".rating-serving span.rating")
      rating = checkin.at(".rating-serving span.rating").attr("class")
      f.write("\s\srating: \"#{rating}\"\n")
    end

    date_time = checkin.at(".time").text
    date = Time.parse(date_time)
    f.write("\s\sdate: \"#{date.day} #{date.strftime('%B')} #{date.year}\"\n")
  end
end

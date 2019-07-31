# frozen_string_literal: true

require_relative "./lib/colorizer"

def project_name
  "Folkingebrew 🍺"
end

## Serve
task :serve do
  puts "== Project: " + project_name.green
  puts "== Start kettle...".yellow
  system "bundle exec middleman serve" || exit(1)
end

task :data do
  system "ruby lib/checkins.rb" || exit(1)
end

## Build the website
task :build do
  puts "== Project: " + project_name
  puts "== Brewing the website..."
  system "bundle exec middleman build" || exit(1)
end

def git_branch_name
  `git rev-parse --abbrev-ref HEAD`
end

desc "Submits PR to GitHub"
task :pr do
  branch_name = git_branch_name
  if branch_name == "master"
    puts "On master branch, not PRing."
    exit 1
  end

  `git push -u origin #{branch_name}`
  `open https://github.com/RonaldDijkstra/folkingebrew/compare/#{branch_name}`
end

# frozen_string_literal: true

require 'rainbow'

def project_name
  'Folkingebrew 🍺'
end

linters = [
  {
    name: 'ESLint',
    language: 'JavaScript',
    command: 'node_modules/.bin/eslint source/**/*.js'
  },
  {
    name: 'stylelint',
    language: 'CSS/SCSS',
    command: 'stylelint **/*.scss'
  },
  {
    name: 'RuboCop',
    language: 'Ruby',
    command: 'rubocop'
  }
]

default_tasks = []

linters.each do |linter|
  desc "Check your #{linter[:language]} files with #{linter[:name]}"
  task linter[:name].downcase.to_sym do
    puts Rainbow(
      "Checking your #{linter[:language]} files with #{linter[:name]}..."
    ).bright.orange
    run_linter(linter[:command])
  end
  default_tasks << linter[:name].downcase.to_sym
end

task default: default_tasks

def run_linter(command)
  output = `#{command}`
  if output.empty?
    puts Rainbow('✔︎ Perfect style!').bright.green
  else
    system command
  end
end

## Serve
task :serve do
  puts Rainbow("== Project: #{project_name}").bright.green
  puts Rainbow('== Warming up the kettle').bright.green
  system 'bundle exec middleman serve' || exit(1)
end

## Build the website
task :build do
  puts Rainbow("== Project: #{project_name}")
  puts Rainbow('== Brewing the website...')
  system 'bundle exec middleman build --verbose' || exit(1)
end

# HTML Proofer to test static output
task :proof do
  puts Rainbow("== Project: #{project_name}")
  system 'bundle exec middleman build --verbose' || exit(1)
  system 'ruby test.rb'
end

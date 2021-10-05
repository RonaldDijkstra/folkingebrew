# Folkingebrew

[![CircleCI branch](https://img.shields.io/circleci/project/github/RonaldDijkstra/folkingebrew/master.svg)](https://circleci.com/gh/RonaldDijkstra/folkingebrew)
[![Netlify Status](https://api.netlify.com/api/v1/badges/5eb7a73a-3aef-4f12-ac97-b957b5a24222/deploy-status)](https://app.netlify.com/sites/folkingebrew/deploys)

Modern Craft Beer from Groningen since 2017 🍻. 

Production: https://www.folkingebrew.nl

## Prerequisites

- Ruby 2.7.1
- Bundler
- Node
- Yarn

## Quick Start 

1. Make sure you have all the prerequisites above installed
2. Clone this repo using `git clone https://github.com/RonaldDijkstra/folkingebrew.git`
3. Move to the appropriate directory: `cd folkingebrew`
4. Run `bin/setup` (make sure it's executable)

## Rake Tasks 

1. Run `rake serve`. You should now be able to view the website at <http://localhost:4567>
2. Build `rake build`. Build the website to the Build folder. 
3. Proof `rake proof`. Use HTML Proofer to check the Output in the Build folder.
4. Linters `rake default`.

## Thanks to 

1. [Middleman](https://middlemanapp.com/) 
2. [Middleman Template by ThoughtBot](https://github.com/thoughtbot/middleman-template) (includes [Bourbon](https://github.com/thoughtbot/bourbon) + [Neat](https://github.com/thoughtbot/neat) + [Bitters](https://github.com/thoughtbot/bitters))
3. [Webpack Middleman Starter by Gabriele Canepa ](https://github.com/gabrielecanepa/middleman-webpack)
4. [HTML Proofer](https://github.com/gjtorikian/html-proofer)
5. [Snipcart](https://snipcart.com)

[![CircleCI branch](https://img.shields.io/circleci/project/github/RonaldDijkstra/folkingebrew/master.svg)](https://circleci.com/gh/RonaldDijkstra/folkingebrew)
[![Netlify Status](https://api.netlify.com/api/v1/badges/5eb7a73a-3aef-4f12-ac97-b957b5a24222/deploy-status)](https://app.netlify.com/sites/folkingebrew/deploys)

# Folkingebrew

Modern Craft Beer from Groningen since 2017. 

## About 

This website is built with Middleman and TailwindCSS with Webpack as external pipeline. This website is deployed to Netlify, uses DatoCMS for content delivery and Snipcart for the cart. 

View in Production: https://www.folkingebrew.nl

## Prerequisites

- Ruby 2.7.1
- Bundler
- Node
- Yarn
- Get API keys at DatoCMS and Snipcart. 

## Quick Start 

1. Make sure you have all the prerequisites above installed or registered
2. Clone this repo using `git clone https://github.com/RonaldDijkstra/folkingebrew.git`
3. Move to the appropriate directory: `cd folkingebrew`
4. Run `bin/setup` (make sure it's executable)
5. Add your `DATO_API_TOKEN`, `SNIPCART_API_KEY` and `GA_ID` to .env
6. Start your local server with `rake serve`
7. View your website at `localhost:4567`

## Thanks to 

1. [Middleman](https://middlemanapp.com/) 
2. [Middleman Template by ThoughtBot](https://github.com/thoughtbot/middleman-template) (includes [Bourbon](https://github.com/thoughtbot/bourbon) + [Neat](https://github.com/thoughtbot/neat) + [Bitters](https://github.com/thoughtbot/bitters))
3. [Webpack Middleman Starter](https://github.com/gabrielecanepa/middleman-webpack) by Gabriele Canepa
4. [HTML Proofer](https://github.com/gjtorikian/html-proofer)
5. [Snipcart](https://snipcart.com)
6. [DatoCMS](https://www.datocms.com/)
7. [TailwindCSS](https://tailwindcss.com/)
